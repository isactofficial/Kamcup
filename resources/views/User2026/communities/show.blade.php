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
                                            <button class="action-btn like-btn {{ $post->current_user_liked ? 'liked' : '' }}" 
                                                    data-feed-id="{{ $post->id }}" title="Suka">
                                                <i class="{{ $post->current_user_liked ? 'fas' : 'far' }} fa-heart"></i>
                                                <span class="like-count">{{ $post->likes_count }}</span>
                                            </button>
                                            <button class="action-btn comment-toggle" 
                                                    data-feed-id="{{ $post->id }}" 
                                                    data-feed-title="{{ $post->title ?? '' }}"
                                                    data-feed-image="{{ $post->image ?? '' }}"
                                                    data-feed-author="{{ $post->user->name ?? 'Admin' }}"
                                                    data-feed-author-photo=""
                                                    data-feed-caption="{{ $post->content }}"
                                                    data-feed-likes="{{ $post->likes_count }}"
                                                    data-feed-liked="{{ $post->current_user_liked ? 'true' : 'false' }}"
                                                    data-feed-meet-date="{{ $post->meet_date ? \Carbon\Carbon::parse($post->meet_date)->format('d M Y, H:i') : '' }}"
                                                    data-feed-meet-location="{{ $post->meet_location ?? '' }}"
                                                    data-feed-meet-max="{{ $post->meet_max_people ?? '' }}"
                                                    title="Komentar">
                                                <i class="far fa-comment"></i>
                                                <span class="comment-count">{{ $post->comments_count ?? 0 }}</span>
                                            </button>
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
                                <button class="btn btn-sm btn-outline-primary px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#agendaTypeModal">
                                    <i class="fas fa-plus me-1"></i> Buat Agenda
                                </button>
                            @endif
                        </div>
                        
                        <div class="row g-4">
                            <!-- Tabs untuk memisahkan agenda -->
                            <ul class="nav nav-tabs mb-4" id="agendaTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="all-agenda-tab" data-bs-toggle="tab" data-bs-target="#all-agenda" type="button" role="tab">
                                        <i class="fas fa-calendar-alt me-2"></i>Semua Agenda
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="recurring-agenda-tab" data-bs-toggle="tab" data-bs-target="#recurring-agenda" type="button" role="tab">
                                        <i class="fas fa-redo me-2"></i>Agenda Berulang
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="single-agenda-tab" data-bs-toggle="tab" data-bs-target="#single-agenda" type="button" role="tab">
                                        <i class="fas fa-calendar-day me-2"></i>Agenda Satu Kali
                                    </button>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content" id="agendaTabsContent">
                                <!-- Tab Semua Agenda -->
                                <div class="tab-pane fade show active" id="all-agenda" role="tabpanel">
                                    @php
                                        $allAgendas = $community->feeds->filter(function($f) {
                                            return !is_null($f->meet_date);
                                        })->sortBy('meet_date');
                                    @endphp

                                    @forelse($allAgendas as $agenda)
                                        <div class="col-md-6">
                                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 agenda-card" 
                                                 style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;"
                                                 onclick="window.location.href='{{ route('user2026.komunitas.agenda.show', [$community->slug, $agenda->id]) }}'">
                                                @if($agenda->image)
                                                    <img src="{{ asset('storage/' . $agenda->image) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                                @else
                                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 150px;">
                                                        <i class="fas fa-calendar-alt text-muted" style="font-size: 3rem;"></i>
                                                    </div>
                                                @endif
                                                <div class="card-body p-4">
                                                    <div class="badge {{ $agenda->is_recurring ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }} mb-2 rounded-pill px-3">
                                                        {{ $agenda->is_recurring ? 'Berulang' : 'Agenda' }}
                                                    </div>
                                                    <h6 class="fw-bold mb-3">{{ $agenda->title }}</h6>
                                                    
                                                    <div class="d-flex flex-column gap-2 mb-4">
                                                        <div class="small text-muted d-flex align-items-center">
                                                            <i class="fas fa-calendar-day me-2 text-primary"></i>
                                                            {{ \Carbon\Carbon::parse($agenda->meet_date)->translatedFormat('d F Y, H:i') }}
                                                        </div>
                                                        <div class="small text-muted d-flex align-items-center">
                                                            <i class="fas fa-clock me-2 text-primary"></i>
                                                            {{ $agenda->meet_duration ?? 0 }} jam
                                                        </div>
                                                        <div class="small text-muted d-flex align-items-center">
                                                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                                            {{ $agenda->meet_location }}
                                                        </div>
                                                        <div class="small text-muted d-flex align-items-center">
                                                            <i class="fas fa-users me-2 text-primary"></i>
                                                            {{ $agenda->joins_count ?? 0 }} / {{ $agenda->meet_max_people }} Peserta
                                                        </div>
                                                        @if($agenda->meet_fee > 0)
                                                        <div class="small text-muted d-flex align-items-center">
                                                            <i class="fas fa-money-bill-wave me-2 text-primary"></i>
                                                            Rp {{ number_format($agenda->meet_fee, 0, ',', '.') }}
                                                        </div>
                                                        @endif
                                                        @if($agenda->meet_gender != 'all')
                                                        <div class="small text-muted d-flex align-items-center">
                                                            <i class="fas fa-venus-mars me-2 text-primary"></i>
                                                            {{ $agenda->meet_gender == 'male' ? 'Pria' : 'Wanita' }}
                                                        </div>
                                                        @endif
                                                        @if($agenda->meet_age_category != 'all')
                                                        <div class="small text-muted d-flex align-items-center">
                                                            <i class="fas fa-user-tag me-2 text-primary"></i>
                                                            {{ ucfirst($agenda->meet_age_category) }}
                                                            @if($agenda->meet_age_category == 'junior') (< 18 th)
                                                            @elseif($agenda->meet_age_category == 'adult') (18-55 th)
                                                            @elseif($agenda->meet_age_category == 'senior') (> 55 th)
                                                            @endif
                                                        </div>
                                                        @endif
                                                        @if($agenda->content)
                                                        <div class="small text-muted d-flex align-items-start">
                                                            <i class="fas fa-sticky-note me-2 text-primary mt-1"></i>
                                                            <span>{{ Str::limit($agenda->content, 100) }}</span>
                                                        </div>
                                                        @endif
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

                                <!-- Tab Agenda Berulang -->
                                <div class="tab-pane fade" id="recurring-agenda" role="tabpanel">
                                    @php
                                        $recurringAgendas = $community->feeds->filter(function($f) {
                                            return !is_null($f->meet_date) && $f->is_recurring;
                                        })->sortBy('meet_date');
                                    @endphp

                                    @forelse($recurringAgendas as $agenda)
                                        <div class="col-md-6">
                                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 agenda-card" 
                                                 style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;"
                                                 onclick="window.location.href='{{ route('user2026.komunitas.agenda.show', [$community->slug, $agenda->id]) }}'">
                                                @if($agenda->image)
                                                    <img src="{{ asset('storage/' . $agenda->image) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                                @else
                                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 150px;">
                                                        <i class="fas fa-redo text-success" style="font-size: 3rem;"></i>
                                                    </div>
                                                @endif
                                                <div class="card-body p-4">
                                                    <div class="badge bg-success-subtle text-success mb-2 rounded-pill px-3">
                                                        <i class="fas fa-redo me-1"></i>Berulang
                                                    </div>
                                                    <h6 class="fw-bold mb-3">{{ $agenda->title }}</h6>
                                                    
                                                    <div class="d-flex flex-column gap-2 mb-4">
                                                        <div class="small text-muted d-flex align-items-center">
                                                            <i class="fas fa-calendar-day me-2 text-success"></i>
                                                            {{ \Carbon\Carbon::parse($agenda->meet_date)->translatedFormat('d F Y, H:i') }}
                                                        </div>
                                                        <div class="small text-muted d-flex align-items-center">
                                                            <i class="fas fa-redo me-2 text-success"></i>
                                                            @if($agenda->recurrence_pattern == 'weekly')
                                                                Setiap {{ \Carbon\Carbon::parse($agenda->meet_date)->translatedFormat('l') }}
                                                            @endif
                                                        </div>
                                                        <div class="small text-muted d-flex align-items-center">
                                                            <i class="fas fa-clock me-2 text-success"></i>
                                                            {{ $agenda->meet_duration ?? 0 }} jam
                                                        </div>
                                                        <div class="small text-muted d-flex align-items-center">
                                                            <i class="fas fa-map-marker-alt me-2 text-success"></i>
                                                            {{ $agenda->meet_location }}
                                                        </div>
                                                        <div class="small text-muted d-flex align-items-center">
                                                            <i class="fas fa-users me-2 text-success"></i>
                                                            {{ $agenda->joins_count ?? 0 }} / {{ $agenda->meet_max_people }} Peserta
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="alert bg-light border-0 text-center py-5 rounded-4">
                                                <i class="fas fa-redo fs-2 mb-3 text-muted"></i>
                                                <h6 class="fw-bold">Belum ada agenda berulang</h6>
                                                <p class="text-muted small mb-0">Buat agenda berulang untuk jadwal rutin mingguan.</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>

                                <!-- Tab Agenda Satu Kali -->
                                <div class="tab-pane fade" id="single-agenda" role="tabpanel">
                                    @php
                                        $singleAgendas = $community->feeds->filter(function($f) {
                                            return !is_null($f->meet_date) && !$f->is_recurring;
                                        })->sortBy('meet_date');
                                    @endphp

                                    @forelse($singleAgendas as $agenda)
                                        <div class="col-md-6">
                                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 agenda-card" 
                                                 style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;"
                                                 onclick="window.location.href='{{ route('user2026.komunitas.agenda.show', [$community->slug, $agenda->id]) }}'">
                                                @if($agenda->image)
                                                    <img src="{{ asset('storage/' . $agenda->image) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                                @else
                                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 150px;">
                                                        <i class="fas fa-calendar-day text-primary" style="font-size: 3rem;"></i>
                                                    </div>
                                                @endif
                                                <div class="card-body p-4">
                                                    <div class="badge bg-primary-subtle text-primary mb-2 rounded-pill px-3">
                                                        <i class="fas fa-calendar-day me-1"></i>Satu Kali
                                                    </div>
                                                    <h6 class="fw-bold mb-3">{{ $agenda->title }}</h6>
                                                    
                                                    <div class="d-flex flex-column gap-2 mb-4">
                                                        <div class="small text-muted d-flex align-items-center">
                                                            <i class="fas fa-calendar-day me-2 text-primary"></i>
                                                            {{ \Carbon\Carbon::parse($agenda->meet_date)->translatedFormat('d F Y, H:i') }}
                                                        </div>
                                                        <div class="small text-muted d-flex align-items-center">
                                                            <i class="fas fa-clock me-2 text-primary"></i>
                                                            {{ $agenda->meet_duration ?? 0 }} jam
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
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="alert bg-light border-0 text-center py-5 rounded-4">
                                                <i class="fas fa-calendar-day fs-2 mb-3 text-muted"></i>
                                                <h6 class="fw-bold">Belum ada agenda satu kali</h6>
                                                <p class="text-muted small mb-0">Buat agenda satu kali untuk jadwal khusus.</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
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

/* Action button styles for community posts */
.action-btn { 
    display: inline-flex; 
    align-items: center; 
    gap: 0.4rem; 
    background: none; 
    border: none; 
    padding: 0.5rem 0.85rem; 
    border-radius: 50px; 
    cursor: pointer; 
    transition: background 0.2s, color 0.2s; 
    font-size: 0.85rem; 
    font-weight: 500; 
    color: #6b7280; 
    font-family: 'DM Sans', sans-serif; 
}

.action-btn i { font-size: 1rem; }

.action-btn:hover { background-color: #fdf2f8; color: #cb2786; }

.action-btn.liked { color: #cb2786; background-color: #fdf2f8; }

.action-btn.saved { color: #f59e0b; background-color: #fef3c7; }

/* ═══════════════════════════════════════════════════════════
   INSTAGRAM-STYLE COMMENT MODAL
   ═══════════════════════════════════════════════════════════ */
#feedCommentModal .modal-dialog {
    max-width: 960px;
    margin: 1.5rem auto;
    height: calc(100vh - 3rem);
}

#feedCommentModal .modal-content {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: row;
}

/* Left: image / post preview */
.ig-modal-left {
    width: 55%;
    background: #fafafa;
    display: flex;
    align-items: stretch;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
    border-right: 1px solid #efefef;
    overflow-y: auto;
}

.ig-modal-left img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    background: #000;
}

/* No-image state: show post content card instead */
.ig-modal-no-img {
    display: flex;
    flex-direction: column;
    width: 100%;
    padding: 32px 28px;
    background: #fafafa;
}

.ig-modal-no-img-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.ig-modal-no-img-title {
    font-family: 'Sora', sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: #00617a;
    line-height: 1.35;
    margin: 0 0 10px;
}

.ig-modal-no-img-text {
    font-size: 0.92rem;
    color: #374151;
    line-height: 1.7;
    margin: 0;
    white-space: pre-wrap;
    word-break: break-word;
}

.ig-modal-no-img-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: linear-gradient(135deg, #cb278615, #00617a15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #cb2786;
    margin-bottom: 20px;
}

.ig-modal-meets-info {
    background: linear-gradient(135deg, rgba(16,185,129,0.07), rgba(5,150,105,0.07));
    border: 1px solid rgba(16,185,129,0.2);
    border-radius: 12px;
    padding: 12px 16px;
    margin-top: 14px;
}

.ig-modal-meets-date {
    font-weight: 600;
    color: #059669;
    font-size: 0.875rem;
    margin-bottom: 4px;
}

.ig-modal-meets-detail {
    font-size: 0.82rem;
    color: #6b7280;
}

/* Right: comment panel */
.ig-modal-right {
    width: 45%;
    display: flex;
    flex-direction: column;
    height: 100%;
    background: #fff;
}

.ig-panel-header {
    display: flex;
    align-items: center;
    padding: 14px 16px;
    border-bottom: 1px solid #efefef;
    flex-shrink: 0;
}

#ig-panel-author-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #cb2786, #00617a);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    margin-right: 12px;
    font-size: 0.8rem;
}

.ig-panel-username {
    font-weight: 600;
    margin: 0;
    font-size: 0.9rem;
    color: #262626;
}

.ig-panel-title {
    color: #8e8e8e;
    margin: 0;
    font-size: 0.8rem;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.2rem;
    color: #8e8e8e;
    cursor: pointer;
    padding: 4px;
    margin-left: auto;
}

.ig-caption-row {
    display: flex;
    align-items: flex-start;
    padding: 12px 16px;
    border-bottom: 1px solid #efefef;
}

#ig-caption-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: linear-gradient(135deg, #cb2786, #00617a);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    margin-right: 8px;
    font-size: 0.7rem;
    flex-shrink: 0;
}

.ig-caption-text {
    margin: 0;
    font-size: 0.85rem;
    line-height: 1.4;
    color: #374151;
}

.ig-action-row {
    display: flex;
    align-items: center;
    padding: 8px 16px;
    gap: 16px;
}

.ig-action-icon {
    background: none;
    border: none;
    font-size: 1.2rem;
    color: #262626;
    cursor: pointer;
    padding: 4px;
    transition: opacity 0.2s;
}

.ig-action-icon:hover {
    opacity: 0.6;
}

.ig-action-icon.liked {
    color: #ed4956;
}

.ig-action-icon-share {
    margin-left: auto;
}

.ig-likes-row {
    padding: 8px 16px;
    font-weight: 600;
    font-size: 0.9rem;
    color: #262626;
}

.ig-comments-list {
    flex: 1;
    overflow-y: auto;
    padding: 8px 0;
}

.ig-comments-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 20px;
    color: #8e8e8e;
    font-size: 0.85rem;
}

.ig-comment-item {
    display: flex;
    align-items: flex-start;
    padding: 8px 16px;
}

.ig-comment-reply {
    margin-left: 48px;
    padding: 4px 16px 4px 0;
}

.ig-comment-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #cb2786, #00617a);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    margin-right: 12px;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.ig-comment-body {
    flex: 1;
}

.ig-comment-bubble {
    background: #f0f2f5;
    border-radius: 18px;
    padding: 8px 12px;
    margin-bottom: 4px;
}

.ig-comment-bubble.ig-comment-deleted {
    background: #f8f9fa;
    opacity: 0.7;
}

.ig-comment-bubble.ig-comment-deleted .ig-comment-text {
    color: #6c757d;
    font-style: italic;
}

.ig-comment-username {
    font-weight: 600;
    font-size: 0.85rem;
    margin: 0 0 2px 0;
    color: #262626;
}

.ig-comment-text {
    font-size: 0.85rem;
    line-height: 1.4;
    margin: 0;
    white-space: pre-wrap;
    color: #374151;
}

.ig-comment-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.8rem;
    color: #8e8e8e;
    padding: 0 4px;
}

.ig-comment-time {
    color: #8e8e8e;
    font-size: 0.73rem;
}

.ig-comment-like-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.8rem;
    color: #8e8e8e;
    padding: 4px 0;
    transition: color 0.15s;
}

.ig-comment-like-btn:hover { color: #ed4956; }
.ig-comment-like-btn.liked { color: #ed4956; }

.ig-reply-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.8rem;
    color: #8e8e8e;
    padding: 4px;
    transition: color 0.15s;
}

.ig-reply-btn:hover { color: #262626; }

.ig-delete-btn {
    background: none;
    border: none;
    cursor: pointer;
    color: #8e8e8e;
    padding: 4px;
    font-size: 0.75rem;
    transition: color 0.15s;
}

.ig-delete-btn:hover { color: #ed4956; }

.ig-replies-list {
    margin-top: 4px;
    border-left: 2px solid #efefef;
    padding-left: 8px;
}

.ig-view-replies-btn {
    background: none;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 8px 0;
    font-size: 0.8rem;
    color: #8e8e8e;
    transition: color 0.15s;
}

.ig-view-replies-btn:hover {
    color: #262626;
}

.ig-view-replies-btn .line {
    flex: 1;
    height: 1px;
    background: #efefef;
}

.ig-view-replies-btn .label {
    padding: 0 8px;
    font-weight: 500;
}

.ig-reply-indicator {
    display: none;
    align-items: center;
    padding: 8px 16px;
    background: #f0f2f5;
    font-size: 0.8rem;
    color: #8e8e8e;
    gap: 8px;
}

.ig-reply-indicator.active {
    display: flex;
}

.ig-reply-indicator strong {
    color: #262626;
}

.cancel-reply {
    background: none;
    border: none;
    color: #8e8e8e;
    cursor: pointer;
    padding: 2px;
    margin-left: auto;
}

.ig-input-area {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border-top: 1px solid #efefef;
    gap: 12px;
}

#ig-my-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #cb2786, #00617a);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.ig-input-wrap {
    flex: 1;
    display: flex;
    align-items: center;
    background: #f0f2f5;
    border-radius: 20px;
    padding: 8px 12px;
}

.ig-comment-input {
    flex: 1;
    border: none;
    background: none;
    outline: none;
    resize: none;
    font-size: 0.85rem;
    line-height: 1.4;
    max-height: 80px;
}

.ig-send-btn {
    background: none;
    border: none;
    color: #0095f6;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    padding: 4px 8px;
    opacity: 0.5;
    transition: opacity 0.2s;
}

.ig-send-btn.active {
    opacity: 1;
}

.ig-send-btn.active:hover {
    opacity: 0.8;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .category-item { white-space: nowrap; flex-shrink: 0; }
    .feeds-main { padding: 16px; }

    #feedCommentModal .modal-dialog { max-width: 100%; margin: 0; height: 100vh; border-radius: 0; }
    #feedCommentModal .modal-content { flex-direction: column; border-radius: 0; }
    .ig-modal-left { width: 100%; height: 45vh; }
    .ig-modal-right { width: 100%; height: 55vh; }
}
</style>
{{-- Agenda Type Selection Modal --}}
<div class="modal fade" id="agendaTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0 shadow-none p-4">
                <h5 class="fw-bold mb-0">Pilih Tipe Agenda</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted mb-4">Pilih jenis agenda yang ingin kamu buat:</p>
                
                <div class="d-grid gap-3">
                    <button type="button" class="btn btn-outline-primary border-2 rounded-4 p-3 text-start" 
                            data-bs-dismiss="modal" 
                            data-bs-toggle="modal" 
                            data-bs-target="#createAgendaModal">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px;">
                                <i class="fas fa-calendar-day fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">One Time</h6>
                                <p class="small text-muted mb-0">Agenda sekali meeting, tanggal dan waktu spesifik</p>
                            </div>
                        </div>
                    </button>
                    
                    <button type="button" class="btn btn-outline-success border-2 rounded-4 p-3 text-start" data-bs-toggle="modal" data-bs-target="#recurringAgendaModal">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px;">
                                <i class="fas fa-redo fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Berulang</h6>
                                <p class="small text-muted mb-0">Agenda rutin (harian/mingguan/bulanan)</p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

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
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Notes</label>
                            <textarea name="content" class="form-control rounded-3 border-light bg-light shadow-none" rows="3" placeholder="Catatan tambahan atau informasi penting..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tanggal & Waktu</label>
                            <input type="datetime-local" name="meet_date" class="form-control rounded-3 border-light bg-light shadow-none" 
                                   min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}" required>
                            <div class="form-text small">Minimal 30 menit dari sekarang</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Durasi (Jam)</label>
                            <input type="number" name="meet_duration" class="form-control rounded-3 border-light bg-light shadow-none" placeholder="Contoh: 2.5" step="0.5" min="0.5" max="24" required>
                            <div class="form-text small">Format: 0.5, 1, 1.5, 2, dst (maksimal 24 jam)</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Lokasi</label>
                            <input type="text" name="meet_location" class="form-control rounded-3 border-light bg-light shadow-none" placeholder="Alamat lengkap atau nama tempat" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Jumlah Peserta</label>
                            <input type="number" name="meet_max_people" class="form-control rounded-3 border-light bg-light shadow-none" placeholder="Maksimal peserta" min="2" max="1000" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Fee (Rp)</label>
                            <input type="number" name="meet_fee" class="form-control rounded-3 border-light bg-light shadow-none" placeholder="0 jika gratis" min="0" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Gender</label>
                            <select name="meet_gender" class="form-control rounded-3 border-light bg-light shadow-none">
                                <option value="all">Semua</option>
                                <option value="male">Pria</option>
                                <option value="female">Wanita</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kategori Usia</label>
                            <select name="meet_age_category" class="form-control rounded-3 border-light bg-light shadow-none">
                                <option value="all">Semua Usia</option>
                                <option value="junior">Junior (&lt; 18 tahun)</option>
                                <option value="adult">Adult (18 - 55 tahun)</option>
                                <option value="senior">Senior (&gt; 55 tahun)</option>
                            </select>
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

{{-- ═══════════════════════════════════════════════════════════
     INSTAGRAM-STYLE COMMENT MODAL
     ═══════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="feedCommentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- LEFT: Post Image / Content -->
            <div class="ig-modal-left" id="ig-modal-img-wrap">
                <div class="ig-modal-no-img">
                    <div class="ig-modal-no-img-icon"><i class="fas fa-newspaper"></i></div>
                    <p class="ig-modal-no-img-text" style="color:#9ca3af;text-align:center;">Pilih postingan untuk dilihat</p>
                </div>
            </div>

            <!-- RIGHT: Comment Panel -->
            <div class="ig-modal-right">

                <!-- Header: author info -->
                <div class="ig-panel-header">
                    <div id="ig-panel-author-avatar"></div>
                    <div>
                        <p class="ig-panel-username" id="ig-panel-author-name">—</p>
                        <p class="ig-panel-title" id="ig-panel-post-title">—</p>
                    </div>
                    <button class="close-btn" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Caption row -->
                <div class="ig-caption-row" id="ig-caption-row" style="display:none;">
                    <div id="ig-caption-avatar"></div>
                    <p class="ig-caption-text" id="ig-caption-text"></p>
                </div>

                <!-- Action icons -->
                <div class="ig-action-row">
                    <button class="ig-action-icon" id="ig-modal-like-btn" title="Suka">
                        <i class="far fa-heart"></i>
                    </button>
                    <button class="ig-action-icon" id="ig-modal-comment-focus-btn" title="Komentar">
                        <i class="far fa-comment"></i>
                    </button>
                    <button class="ig-action-icon ig-action-icon-share" id="ig-modal-share-btn" title="Bagikan">
                        <i class="fas fa-share-nodes"></i>
                    </button>
                </div>

                <!-- Likes count -->
                <div class="ig-likes-row">
                    <span id="ig-modal-likes-count">0</span> suka
                </div>

                <!-- Comments list -->
                <div class="ig-comments-list" id="ig-comments-list">
                    <div class="ig-comments-loading">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                        <span>Memuat komentar...</span>
                    </div>
                </div>

                <!-- Reply indicator -->
                <div class="ig-reply-indicator" id="ig-reply-indicator">
                    <i class="fas fa-reply"></i>
                    <span id="ig-reply-label">Membalas <strong></strong></span>
                    <button class="cancel-reply" id="ig-cancel-reply"><i class="fas fa-times"></i></button>
                </div>

                <!-- Input area -->
                <div class="ig-input-area">
                    <div id="ig-my-avatar"></div>
                    <div class="ig-input-wrap">
                        <textarea class="ig-comment-input"
                                  id="ig-comment-input"
                                  placeholder="Tambah komentar..."
                                  rows="1"></textarea>
                        <button class="ig-send-btn" id="ig-send-btn">Kirim</button>
                    </div>
                </div>

            </div><!-- /.ig-modal-right -->
        </div><!-- /.modal-content -->
    </div>
</div>

{{-- Create Recurring Agenda Modal --}}
<div class="modal fade" id="recurringAgendaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0 shadow-none p-4">
                <h5 class="modal-title fw-bold">Buat Agenda Berulang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user2026.komunitas.agenda.store', $community->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 rounded-3 mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Agenda berulang akan membuat beberapa agenda sesuai pola yang dipilih. Setiap agenda akan memiliki detail yang sama.
                    </div>
                    
                    <div class="row g-3">
                        <!-- Basic Info -->
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Judul Agenda</label>
                            <input type="text" name="title" class="form-control rounded-3 border-light bg-light shadow-none" placeholder="Contoh: Latihan Basket Rutin" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Notes</label>
                            <textarea name="content" class="form-control rounded-3 border-light bg-light shadow-none" rows="3" placeholder="Catatan tambahan atau informasi penting..."></textarea>
                        </div>
                        
                        <!-- Recurring Pattern -->
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Pola Berulang</label>
                            <div class="form-control rounded-3 border-light bg-light shadow-none" style="background-color: #f8f9fa;">
                                <span class="text-muted">Mingguan</span>
                            </div>
                            <input type="hidden" name="recurrence_pattern" value="weekly">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Hari</label>
                            <select name="recurrence_days" class="form-control rounded-3 border-light bg-light shadow-none" required>
                                <option value="">Pilih hari</option>
                                <option value="1">Senin</option>
                                <option value="2">Selasa</option>
                                <option value="3">Rabu</option>
                                <option value="4">Kamis</option>
                                <option value="5">Jumat</option>
                                <option value="6">Sabtu</option>
                                <option value="7">Minggu</option>
                            </select>
                            <div class="form-text small">Pilih satu hari untuk agenda mingguan. Untuk beberapa hari, buat agenda baru.</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Waktu Mulai</label>
                            <input type="time" name="meet_time" class="form-control rounded-3 border-light bg-light shadow-none" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Durasi (Jam)</label>
                            <input type="number" name="meet_duration" class="form-control rounded-3 border-light bg-light shadow-none" placeholder="Contoh: 2" step="0.5" min="0.5" max="24" required>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Lokasi</label>
                            <input type="text" name="meet_location" class="form-control rounded-3 border-light bg-light shadow-none" placeholder="Alamat lengkap atau nama tempat" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Jumlah Peserta</label>
                            <input type="number" name="meet_max_people" class="form-control rounded-3 border-light bg-light shadow-none" placeholder="Maksimal peserta" min="2" max="1000" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Fee (Rp)</label>
                            <input type="number" name="meet_fee" class="form-control rounded-3 border-light bg-light shadow-none" placeholder="0 jika gratis" min="0" value="0">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Gender</label>
                            <select name="meet_gender" class="form-control rounded-3 border-light bg-light shadow-none">
                                <option value="all">Semua</option>
                                <option value="male">Pria</option>
                                <option value="female">Wanita</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kategori Usia</label>
                            <select name="meet_age_category" class="form-control rounded-3 border-light bg-light shadow-none">
                                <option value="all">Semua Usia</option>
                                <option value="junior">Junior (&lt; 18 tahun)</option>
                                <option value="adult">Adult (18 - 55 tahun)</option>
                                <option value="senior">Senior (&gt; 55 tahun)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Auto Upload</label>
                            <select name="auto_upload_days" class="form-control rounded-3 border-light bg-light shadow-none">
                                <option value="">Pilih kapan auto upload</option>
                                <option value="1">1 hari sebelum</option>
                                <option value="3">3 hari sebelum</option>
                                <option value="7">1 minggu sebelum</option>
                                <option value="14">2 minggu sebelum</option>
                                <option value="30">1 bulan sebelum</option>
                            </select>
                            <div class="form-text small">Agenda akan otomatis muncul X hari sebelum jadwal</div>
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
                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="fas fa-redo me-2"></i>Buat Agenda Berulang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js"></script>
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
                const response = await fetch(`/user/komunitas/${communitySlug}/messages`);
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
                    const response = await fetch(`/user/komunitas/${communitySlug}/messages`, {
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
                .listen('.MessageSent', (e) => {
                    // console.log('Community message received:', e);
                    if (parseInt(e.user.id) !== parseInt(currentUserId)) {
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
                if (countContainer) {
                    const maxPeople = countContainer.innerText.split(' / ')[1];
                    countContainer.innerHTML = `<i class="fas fa-users me-2 text-primary"></i> ${data.count} / ${maxPeople}`;
                }
                
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

    // --- LIKE FUNCTIONALITY FOR COMMUNITY POSTS ---
    document.addEventListener('click', function(e) {
        // Handle like button clicks
        if (e.target.closest('.like-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.like-btn');
            const feedId = btn.dataset.feedId;
            
            if (!feedId) {
                console.error('No feed ID found on like button');
                return;
            }
            
            console.log('Like button clicked for feed:', feedId);
            
            // Show loading state
            const originalHtml = btn.innerHTML;
            btn.style.opacity = '0.5';
            btn.style.pointerEvents = 'none';
            
            fetch(`/feeds/${feedId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Like response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Like response data:', data);
                
                // Update button state
                btn.classList.toggle('liked', data.liked);
                const icon = btn.querySelector('i');
                if (icon) {
                    icon.classList.toggle('far', !data.liked);
                    icon.classList.toggle('fas', data.liked);
                }
                
                const countElement = btn.querySelector('.like-count');
                if (countElement) {
                    countElement.textContent = data.count;
                }
                
                // Update data attributes for comment toggle
                btn.dataset.feedLiked = data.liked ? 'true' : 'false';
                btn.dataset.feedLikes = data.count;
                
                console.log('Like button updated successfully');
            })
            .catch(error => {
                console.error('Error toggling like:', error);
                // Show error message to user
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger alert-sm position-fixed top-0 start-50 translate-middle-x mt-3';
                errorDiv.style.zIndex = '9999';
                errorDiv.textContent = 'Gagal memperbarui like. Silakan coba lagi.';
                document.body.appendChild(errorDiv);
                
                setTimeout(() => {
                    errorDiv.remove();
                }, 3000);
            })
            .finally(() => {
                btn.style.opacity = '1';
                btn.style.pointerEvents = 'auto';
            });
        }
        
        // Handle comment button clicks
        if (e.target.closest('.comment-toggle')) {
            const btn = e.target.closest('.comment-toggle');
            const feedId = btn.dataset.feedId;
            
            if (!feedId) return;
            
            // Open comment modal
            openCommentModal(btn);
        }
    });

    // ═══════════════════════════════════════════════════════════
    // INSTAGRAM-STYLE COMMENT MODAL FUNCTIONALITY
    // ═══════════════════════════════════════════════════════════
    let currentFeedId = null;
    let replyToId = null;
    let replyToName = null;

    function avatarHtml(photo, name, size = 36, cls = '') {
        const initial = (name || '?').charAt(0).toUpperCase();
        if (photo) {
            return `<img src="/storage/${photo}" alt="${name}"
                         style="width:${size}px;height:${size}px;border-radius:50%;object-fit:cover;flex-shrink:0;"
                         onerror="this.onerror=null;this.src='/assets/img/profile-placeholder.png';" class="${cls}">`;
        }
        return `<div style="width:${size}px;height:${size}px;border-radius:50%;background:linear-gradient(135deg,#cb2786,#00617a);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:${Math.round(size*0.38)}px;flex-shrink:0;" class="${cls}">${initial}</div>`;
    }

    function buildNoImgContent(title, caption, meetDate, meetLoc, meetMax) {
        const isMeets = !!meetDate;
        const icon    = isMeets ? 'fas fa-calendar-plus' : 'fas fa-newspaper';

        let meetsBlock = '';
        if (isMeets) {
            meetsBlock = `<div class="ig-modal-meets-info">
                <div class="ig-modal-meets-date"><i class="fas fa-calendar me-1"></i>${meetDate}${meetLoc ? ' • ' + escapeHtml(meetLoc) : ''}</div>
                ${meetMax ? `<div class="ig-modal-meets-detail"><i class="fas fa-users me-1"></i>Maks ${meetMax} orang</div>` : ''}
            </div>`;
        }

        return `<div class="ig-modal-no-img">
            <div class="ig-modal-no-img-icon"><i class="${icon}"></i></div>
            ${title ? `<h2 class="ig-modal-no-img-title">${escapeHtml(title)}</h2>` : ''}
            ${caption ? `<p class="ig-modal-no-img-text">${escapeHtml(caption)}</p>` : ''}
            ${meetsBlock}
        </div>`;
    }

    function escapeHtml(str) {
        return (str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function openCommentModal(btn) {
        const feedId = btn.dataset.feedId;
        const title = btn.dataset.feedTitle || '';
        const image = btn.dataset.feedImage || '';
        const author = btn.dataset.feedAuthor || 'KAMCUP';
        const photo = btn.dataset.feedAuthorPhoto || '';
        const caption = btn.dataset.feedCaption || '';
        const likes = +btn.dataset.feedLikes || 0;
        const liked = btn.dataset.feedLiked === 'true';
        const meetDate = btn.dataset.feedMeetDate || '';
        const meetLoc = btn.dataset.feedMeetLocation || '';
        const meetMax = btn.dataset.feedMeetMax || '';

        // Store feed id on modal for sync
        $('#feedCommentModal').data('feed-id', feedId);
        currentFeedId = feedId;

        // Image atau konten post
        const $imgWrap = $('#ig-modal-img-wrap');
        if (image) {
            $imgWrap.html(`<img src="/storage/${image}" alt="${escapeHtml(title)}"
                                style="width:100%;height:100%;object-fit:contain;background:#000;"
                                onerror="this.onerror=null; $(this).closest('#ig-modal-img-wrap').html(buildNoImgContent('${escapeHtml(title).replace(/'/g,"\\'")}','${escapeHtml(caption).replace(/'/g,"\\'")}','${meetDate}','${escapeHtml(meetLoc)}','${meetMax}'));">`);
        } else {
            $imgWrap.html(buildNoImgContent(title, caption, meetDate, meetLoc, meetMax));
        }

        // Author header
        $('#ig-panel-author-avatar').html(avatarHtml(photo, author, 36));
        $('#ig-panel-author-name').text(author);
        $('#ig-panel-post-title').text(title || 'Postingan Komunitas');

        // Caption
        if (caption) {
            $('#ig-caption-avatar').html(avatarHtml(photo, author, 32));
            $('#ig-caption-text').html(`<strong>${escapeHtml(author)}</strong> ${escapeHtml(caption)}`);
            $('#ig-caption-row').show();
        } else {
            $('#ig-caption-row').hide();
        }

        // Like state
        const $likeBtnModal = $('#ig-modal-like-btn');
        $likeBtnModal.toggleClass('liked', liked)
            .find('i').toggleClass('far', !liked).toggleClass('fas', liked);
        $('#ig-modal-likes-count').text(likes);

        // My avatar in input
        $('#ig-my-avatar').html(avatarHtml('', '{{ Auth::user()->name }}', 32));

        // Reset reply state & input
        resetReply();
        $('#ig-comment-input').val('').css('height', '42px');
        $('#ig-send-btn').removeClass('active');

        // Show modal then load comments
        $('#feedCommentModal').modal('show');
        loadComments(feedId);
    }

    function loadComments(feedId) {
        const $commentsList = $('#ig-comments-list');
        $commentsList.html(`
            <div class="ig-comments-loading">
                <div class="spinner-border spinner-border-sm" role="status"></div>
                <span>Memuat komentar...</span>
            </div>
        `);

        fetch(`/feeds/${feedId}/comments`)
            .then(response => response.json())
            .then(comments => {
                if (comments.length === 0) {
                    $commentsList.html(`
                        <div class="text-center py-4 text-muted">
                            <i class="far fa-comment mb-2 d-block"></i>
                            <span>Belum ada komentar</span>
                        </div>
                    `);
                } else {
                    renderComments(comments);
                }
            })
            .catch(error => {
                console.error('Error loading comments:', error);
                $commentsList.html(`
                    <div class="text-center py-4 text-danger">
                        <span>Gagal memuat komentar</span>
                    </div>
                `);
            });
    }

    function renderComments(comments) {
        const $commentsList = $('#ig-comments-list');
        if (!comments || comments.length === 0) {
            $commentsList.html(`
                <div class="text-center py-4 text-muted">
                    <i class="far fa-comment mb-2 d-block"></i>
                    <span>Belum ada komentar</span>
                </div>
            `);
            return;
        }
        $commentsList.empty();
        comments.forEach(c => $commentsList.append(buildCommentEl(c, false)));
        $commentsList.scrollTop(0);
    }

    function buildCommentEl(c, isReply) {
        const photo   = c.user?.profile?.profile_photo || '';
        const name    = c.user?.name || 'User';
        const timeAgo = moment(c.created_at).fromNow();
        const isOwn   = c.user_id == {{ Auth::id() }}; // Use loose comparison for string vs number
        const replies = c.children || [];

        // Replies always start COLLAPSED — harus klik "Lihat N balasan" dulu
        const repliesHtml = !isReply
            ? `<div class="ig-replies-list" id="replies-${c.id}" style="display:none;">
                ${replies.map(r => buildCommentEl(r, true)).join('')}
               </div>`
            : '';

        // Toggle button — tampil hanya kalau ada balasan dan ini bukan reply
        const viewRepliesBtn = (!isReply && replies.length > 0)
            ? `<button class="ig-view-replies-btn" data-comment-id="${c.id}" data-count="${replies.length}" data-open="0">
                <span class="line"></span>
                <span class="label">Lihat ${replies.length} balasan</span>
                <span class="line"></span>
               </button>`
            : '';

        return `
        <div class="ig-comment-item ${isReply ? 'ig-comment-reply' : ''}" id="comment-${c.id}" data-comment-id="${c.id}" data-user-id="${c.user_id}">
            ${avatarHtml(photo, name, 32, 'ig-comment-avatar')}
            <div class="ig-comment-body">
                <div class="ig-comment-bubble">
                    <p class="ig-comment-username">${name}</p>
                    <p class="ig-comment-text">${escapeHtml(c.content)}</p>
                </div>
                <div class="ig-comment-meta">
                    <span class="ig-comment-time">${timeAgo}</span>
                    ${!isReply ? `<button class="ig-reply-btn" data-comment-id="${c.id}" data-comment-name="${name}">Balas</button>` : ''}
                    ${isOwn ? `<button class="ig-delete-btn" data-comment-id="${c.id}" title="Hapus"><i class="fas fa-trash-alt"></i></button>` : ''}
                </div>
                ${repliesHtml}
                ${viewRepliesBtn}
            </div>
        </div>`;
    }

    function resetReply() {
        replyToId = null;
        replyToName = null;
        $('#ig-reply-indicator').removeClass('active');
        $('#ig-comment-input').attr('placeholder', 'Tambah komentar...');
    }

    // Modal like button
    $('#ig-modal-like-btn').on('click', function() {
        if (!currentFeedId) return;
        // Delegate to the card's like button to keep in sync
        $(`.like-btn[data-feed-id="${currentFeedId}"]`).trigger('click');
    });

    // Comment input handling
    $('#ig-comment-input').on('input', function() {
        const hasText = $(this).val().trim().length > 0;
        $('#ig-send-btn').toggleClass('active', hasText);
        
        // Auto resize
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 80) + 'px';
    });

    // Reply functionality
    $(document).on('click', '.ig-reply-btn', function() {
        const commentId = $(this).data('comment-id');
        const commentName = $(this).data('comment-name');
        
        replyToId = commentId;
        replyToName = commentName;

        const $ind = $('#ig-reply-indicator');
        $ind.find('strong').text(replyToName);
        $ind.addClass('active');

        $('#ig-comment-input').focus().val(`@${replyToName} `).trigger('input');
    });

    // View replies toggle
    $(document).on('click', '.ig-view-replies-btn', function() {
        const $btn = $(this);
        const cId = $btn.data('comment-id');
        const $rList = $(`#replies-${cId}`);
        const isOpen = $btn.data('open') === 1 || $btn.data('open') === '1';

        if (isOpen) {
            $rList.slideUp(200);
            $btn.find('.label').text(`Lihat ${$btn.data('count')} balasan`);
            $btn.data('open', 0);
        } else {
            $rList.slideDown(200);
            $btn.find('.label').text(`Sembunyikan ${$btn.data('count')} balasan`);
            $btn.data('open', 1);
        }
    });

    // Send comment
    $('#ig-send-btn').on('click', function() {
        if (!$(this).hasClass('active') || !currentFeedId) return;
        
        let content = $('#ig-comment-input').val().trim();
        if (!content) return;

        // Remove @username prefix if it's a reply
        if (replyToName && content.startsWith(`@${replyToName} `)) {
            content = content.substring(`@${replyToName} `.length);
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/feeds/${currentFeedId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                content: content,
                parent_id: replyToId || null
            })
        })
        .then(response => response.json())
        .then(newComment => {
            if (replyToId) {
                // It's a reply - add to parent's replies list
                const $repliesList = $(`#replies-${replyToId}`);
                if ($repliesList.length === 0) {
                    // Create replies list if it doesn't exist
                    $(`#comment-${replyToId} .ig-comment-body`).append(`
                        <div class="ig-replies-list" id="replies-${replyToId}"></div>
                    `);
                }
                $(`#replies-${replyToId}`).append(buildCommentEl(newComment, true)).show();
                
                // Update view replies button or create it
                const $viewBtn = $(`#comment-${replyToId} .ig-view-replies-btn`);
                if ($viewBtn.length > 0) {
                    const currentCount = $viewBtn.data('count');
                    $viewBtn.data('count', currentCount + 1);
                    if ($viewBtn.data('open') === 1) {
                        $viewBtn.find('.label').text(`Sembunyikan ${currentCount + 1} balasan`);
                    } else {
                        $viewBtn.find('.label').text(`Lihat ${currentCount + 1} balasan`);
                    }
                } else {
                    // Create view replies button
                    $(`#comment-${replyToId} .ig-comment-body`).append(`
                        <button class="ig-view-replies-btn" data-comment-id="${replyToId}" data-count="1" data-open="1">
                            <span class="line"></span>
                            <span class="label">Sembunyikan 1 balasan</span>
                            <span class="line"></span>
                        </button>
                    `);
                }
            } else {
                // It's a new parent comment - add to main list
                const $commentsList = $('#ig-comments-list');
                
                // Remove empty state if exists
                $commentsList.find('.text-center').remove();
                
                // Add new comment
                $commentsList.append(buildCommentEl(newComment, false));
            }
            
            // Update comment count on button
            const $commentBtn = $(`.comment-toggle[data-feed-id="${currentFeedId}"]`);
            const currentCount = parseInt($commentBtn.find('.comment-count').text()) || 0;
            $commentBtn.find('.comment-count').text(currentCount + 1);
            
            // Reset input
            $('#ig-comment-input').val('').css('height', '42px');
            $('#ig-send-btn').removeClass('active');
            resetReply();
        })
        .catch(error => {
            console.error('Error posting comment:', error);
            alert('Gagal mengirim komentar. Silakan coba lagi.');
        });
    });

    // Delete comment functionality
    $(document).on('click', '.ig-delete-btn', function() {
        const $btn = $(this);
        const commentId = $btn.data('comment-id');
        
        if (!confirm('Apakah Anda yakin ingin menghapus komentar ini?')) {
            return;
        }
        
        console.log('Attempting to delete comment:', commentId);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Use the correct route for feed comments
        fetch(`/feeds/${commentId}/comment`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Delete response status:', response.status);
            console.log('Delete response headers:', response.headers);
            
            if (!response.ok) {
                // Try to get error details
                return response.text().then(text => {
                    console.log('Error response body:', text);
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Delete response data:', data);
            
            if (data.success) {
                // Hard delete: remove comment from DOM
                const $comment = $(`#comment-${commentId}`);
                const $parent = $comment.parent();
                const isReply = $comment.hasClass('ig-comment-reply');
                
                if (isReply) {
                    // It's a reply - update parent's reply count and view button
                    const $parentComment = $comment.closest('.ig-comment-body').parent();
                    const parentId = $parentComment.data('comment-id');
                    const $viewBtn = $(`#comment-${parentId} .ig-view-replies-btn`);
                    
                    $comment.remove();
                    
                    // Update view replies button
                    if ($viewBtn.length > 0) {
                        const currentCount = $viewBtn.data('count') - 1;
                        if (currentCount > 0) {
                            $viewBtn.data('count', currentCount);
                            if ($viewBtn.data('open') === 1) {
                                $viewBtn.find('.label').text(`Sembunyikan ${currentCount} balasan`);
                            } else {
                                $viewBtn.find('.label').text(`Lihat ${currentCount} balasan`);
                            }
                        } else {
                            // Remove view button if no more replies
                            $viewBtn.remove();
                            // Remove replies list if empty
                            $(`#replies-${parentId}`).remove();
                        }
                    }
                } else {
                    // It's a parent comment - remove entire comment with replies
                    $comment.remove();
                }
                
                // Update comment count on button
                const $commentBtn = $(`.comment-toggle[data-feed-id="${currentFeedId}"]`);
                const currentCount = parseInt($commentBtn.find('.comment-count').text()) || 0;
                $commentBtn.find('.comment-count').text(Math.max(0, currentCount - 1));
                
                // Show success message
                if (data.message) {
                    // Show temporary success notification instead of alert
                    const notification = $(`
                        <div class="alert alert-success alert-dismissible fade show position-fixed" 
                             style="top: 20px; right: 20px; z-index: 9999; min-width: 250px;">
                            <i class="fas fa-check-circle me-2"></i>${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                    $('body').append(notification);
                    setTimeout(() => notification.fadeOut(500, () => notification.remove()), 3000);
                }
            } else {
                console.log('Delete failed, data:', data);
                // Show specific error message from server
                alert(data.message || 'Gagal menghapus komentar. Silakan coba lagi.');
            }
        })
        .catch(error => {
            console.error('Error deleting comment:', error);
            alert('Gagal menghapus komentar. Silakan coba lagi. Error: ' + error.message);
        });
    });

    // Reset state on modal close
    $('#feedCommentModal').on('hidden.bs.modal', function() {
        currentFeedId = null;
        replyToId = null;
        resetReply();
    });
</script>

<style>
.agenda-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 97, 122, 0.15) !important;
}

/* Multiple select styling */
select[name="recurrence_days"] {
    min-height: 80px !important;
}

select[name="recurrence_days"] option {
    padding: 8px 12px !important;
    margin: 2px 0 !important;
}

select[name="recurrence_days"] option:checked {
    background-color: #00617a !important;
    color: white !important;
    font-weight: bold !important;
}
</style>
@endpush

@endsection
