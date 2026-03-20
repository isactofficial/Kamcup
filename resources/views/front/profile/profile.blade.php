@extends('layouts/master_nav')

@section('title', 'Profile')

@section('content')
<div class="container py-5">
    <h2 class="text-center mt-5 scroll-reveal">Profile</h2>

    <div class="text-center my-3 scroll-reveal">
        <img src="{{ Auth::user()->profile && Auth::user()->profile->profile_photo ? asset('storage/' . Auth::user()->profile->profile_photo) : asset('assets/img/profile-placeholder.png') }}"
            alt="Profile Photo" class="img-fluid img-square-profile">
    </div>

    <h4 class="text-center mt-4 mb-3 profile-section-title scroll-reveal">Informasi Dasar</h4>

    <div class="card shadow-sm mb-4 profile-info-card scroll-reveal">
        <div class="card-body position-relative">
            <a href="{{ route('profile.edit') }}"
                class="btn btn-link p-0 position-absolute top-0 end-0 mt-3 me-3 edit-profile-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path
                        d="M15.502 1.94a.5.5 0 0 1 .706 0L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.121z" />
                    <path fill-rule="evenodd"
                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg>
            </a>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Nama:</div>
                <div class="col-md-9">{{ Auth::user()->profile->name ?? (Auth::user()->name ?? '-') }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Tanggal Lahir:</div>
                <div class="col-md-9">{{ Auth::user()->profile->birthdate ?? '-' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Jenis Kelamin:</div>
                <div class="col-md-9">{{ Auth::user()->profile->gender ?? '-' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Email:</div>
                <div class="col-md-9">{{ Auth::user()->profile->email ?? (Auth::user()->email ?? '-') }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Nomor Telepon:</div>
                <div class="col-md-9">{{ Auth::user()->profile->phone_number ?? '-' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Akun Sosial Media:</div>
                <div class="col-md-9">{{ Auth::user()->profile->social_media ?? '-' }}</div>
            </div>

@if(!\Illuminate\Support\Facades\Cache::get('hide_user_pages', false) || Auth::user()->isAdmin())
            <div class="row mt-4 mb-3">
                <div class="col-12">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <a href="{{ route('user2026.komunitas') }}" class="btn w-100 px-4 py-3" 
                               style="background-color: #cb2786; color: #fff; border-radius: 12px; font-weight: 600; font-size: 1rem; box-shadow: 0 4px 12px rgba(203,39,134,0.3); border: none;">
                                <i class="fas fa-users me-2"></i>Komunitas
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('user2026.teman') }}" class="btn w-100 px-4 py-3" 
                               style="background-color: #f4b704; color: #212529; border-radius: 12px; font-weight: 600; font-size: 1rem; box-shadow: 0 4px 12px rgba(244,183,4,0.3); border: none;">
                                <i class="fas fa-user-friends me-2"></i>Teman
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('user2026.feeds') }}" class="btn w-100 px-4 py-3" 
                               style="background-color: #00617a; color: #fff; border-radius: 12px; font-weight: 600; font-size: 1rem; box-shadow: 0 4px 12px rgba(0,97,122,0.3); border: none;">
                                <i class="fas fa-rss me-2"></i>Feeds
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <ul class="nav nav-tabs mb-4 scroll-reveal" id="profileTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link @if (request('active_tab') == 'tim' || !request('active_tab')) active @endif" id="tim-tab" data-bs-toggle="tab"
                href="#tim" role="tab" aria-controls="tim" aria-selected="true">Tim</a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if (request('active_tab') == 'event-saya') active @endif" id="event-saya-tab" data-bs-toggle="tab"
                href="#event-saya" role="tab" aria-controls="event-saya" aria-selected="false">Event Saya</a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if (request('active_tab') == 'permohonan') active @endif" id="permohonan-tab" data-bs-toggle="tab"
                href="#permohonan" role="tab" aria-controls="permohonan" aria-selected="false">Permohonan Tuan Rumah</a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if (request('active_tab') == 'donasi-saya') active @endif" id="donasi-saya-tab"
                data-bs-toggle="tab" href="#donasi-saya" role="tab" aria-controls="donasi-saya"
                aria-selected="false">Donasi Saya</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        {{-- Tab 'Tim' --}}
        <div class="tab-pane fade @if (request('active_tab') == 'tim' || !request('active_tab')) show active @endif" id="tim" role="tabpanel" aria-labelledby="tim-tab">
            @if (!isset($hasTeam) || !$hasTeam)
                <div class="d-flex justify-content-center align-items-center scroll-reveal" style="min-height: 200px; border: 1px dashed #ccc; border-radius: 8px;">
                    <a href="{{ route('team.create') }}" class="text-decoration-none text-muted" style="font-size: 3rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                        </svg>
                        <p class="mt-3 fs-5">Buat Tim</p>
                    </a>
                </div>
            @else
                <h4 class="text-center mt-4 mb-3 profile-section-title scroll-reveal">Detail Tim</h4>
                <div class="card shadow-sm mb-4 profile-info-card scroll-reveal">
                    <div class="card-body position-relative">
                        <a href="{{ route('team.edit', Crypt::encryptString($firstTeam->id)) }}" class="btn btn-link p-0 position-absolute top-0 end-0 mt-3 me-3 edit-profile-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 .706 0L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.121z" />
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>
                        </a>

                        <div class="text-center mb-3">
                            <img src="{{ $firstTeam->logo ? asset('storage/' . $firstTeam->logo) : asset('assets/img/profile-placeholder.png') }}" alt="Team Logo" class="img-fluid img-square-team">
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Nama Tim:</div>
                            <div class="col-md-8">{{ $firstTeam->name }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Status Tim:</div>
                            <div class="col-md-8">
                                @if ($firstTeam->status == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                                @elseif ($firstTeam->status == 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif ($firstTeam->status == 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($firstTeam->status) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Manajer:</div>
                            <div class="col-md-8">{{ $firstTeam->manager_name }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Kontak:</div>
                            <div class="col-md-8">{{ $firstTeam->contact }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Lokasi:</div>
                            <div class="col-md-8">{{ $firstTeam->location }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Kategori Gender:</div>
                            <div class="col-md-8">{{ ucfirst($firstTeam->gender_category) }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Jumlah Anggota:</div>
                            <div class="col-md-8">{{ $firstTeam->members->count() }} / {{ $firstTeam->member_count }}</div>
                        </div>
                        @if ($firstTeam->description)
                            <div class="row mb-2">
                                <div class="col-md-4 fw-bold">Deskripsi:</div>
                                <div class="col-md-8">{{ $firstTeam->description }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <h4 class="text-center mt-4 mb-3 profile-section-title scroll-reveal">Anggota Tim</h4>
                <div class="row row-cols-2 row-cols-md-5 g-3 d-flex align-items-stretch scroll-reveal-stagger">
                    @for ($i = 0; $i < $firstTeam->member_count; $i++)
                        <div class="col scroll-reveal-item">
                            @php
                                $member = $teamMembers->get($i) ?? null;
                            @endphp

                            @if ($member)
                                <div class="card h-100 shadow-sm text-center d-flex flex-column justify-content-center align-items-center text-dark member-card-wrapper">
                                    <div class="d-flex flex-column h-100">
                                        <a href="{{ route('team.members.edit', ['team' => Crypt::encryptString($firstTeam->id), 'member' => Crypt::encryptString($member->id)]) }}" class="member-card-link" style="padding: 1rem; width: 100%;">
                                            <div class="card-body">
                                                @if ($member->photo)
                                                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="img-fluid img-square-team-member mb-2">
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" class="bi bi-person-circle text-muted mb-2" viewBox="0 0 16 16">
                                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                                    </svg>
                                                @endif
                                                <h6 class="card-title mb-0">{{ $member->name ?? 'Anggota Tim' }}</h6>
                                                <p class="card-text text-muted small">{{ $member->position ?? 'Peran' }}</p>
                                            </div>
                                        </a>
                                        @if ($firstTeam->members->count() > 1)
                                            <form action="{{ route('team.members.destroy', ['team' => Crypt::encryptString($firstTeam->id), 'member' => Crypt::encryptString($member->id)]) }}" method="POST" class="d-block w-100 px-3 pb-2 mt-auto">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDeleteMember(event, this.parentElement)" class="btn btn-danger btn-sm w-100">
                                                    <i class="fas fa-trash me-1"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('team.members.create', Crypt::encryptString($firstTeam->id)) }}" class="card h-100 shadow-sm text-center d-flex flex-column justify-content-center align-items-center text-decoration-none text-muted member-card-link add-member-card">
                                    <div class="card-body">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                                            <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                            <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z" />
                                        </svg>
                                        <h6 class="card-title mt-2 mb-0">Tambah Anggota</h6>
                                        <p class="card-text text-muted small">Slot Kosong {{ $i + 1 }}</p>
                                    </div>
                                </a>
                            @endif
                        </div>
                    @endfor
                </div>
            @endif
        </div>

        {{-- Tab 'Event Saya' --}}
        <div class="tab-pane fade @if (request('active_tab') == 'event-saya') show active @endif" id="event-saya" role="tabpanel" aria-labelledby="event-saya-tab">
            @if (!isset($registeredTournaments) || $registeredTournaments->isEmpty())
                <p class="text-center text-muted scroll-reveal">Anda belum terdaftar ke lomba manapun.</p>
            @else
                @foreach ($registeredTournaments as $registration)
                    <div class="card shadow-sm mb-3 scroll-reveal">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4>{{ $registration->tournament->title ?? 'Nama Turnamen' }}</h4>
                                @php
                                    $badgeClass = '';
                                    switch ($registration->status) {
                                        case 'pending':
                                            $badgeClass = 'bg-warning text-dark';
                                            break;
                                        case 'approved':
                                            $badgeClass = 'bg-success';
                                            break;
                                        case 'rejected':
                                            $badgeClass = 'bg-danger';
                                            break;
                                        case 'completed':
                                            $badgeClass = 'bg-primary';
                                            break;
                                        default:
                                            $badgeClass = 'bg-secondary';
                                            break;
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($registration->status ?? 'Status') }}</span>
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                @php
                                    $progressWidth = 0;
                                    $progressBarClass = '';
                                    switch ($registration->status) {
                                        case 'pending':
                                            $progressWidth = 25;
                                            $progressBarClass = 'bg-warning';
                                            break;
                                        case 'approved':
                                            $progressWidth = 50;
                                            $progressBarClass = 'bg-info';
                                            break;
                                        case 'completed':
                                            $progressWidth = 100;
                                            $progressBarClass = 'bg-success';
                                            break;
                                        case 'rejected':
                                            $progressWidth = 10;
                                            $progressBarClass = 'bg-danger';
                                            break;
                                        default:
                                            $progressWidth = 0;
                                            $progressBarClass = 'bg-secondary';
                                            break;
                                    }
                                @endphp
                                <div class="progress-bar {{ $progressBarClass }}" role="progressbar" style="width: {{ $progressWidth }}%" aria-valuenow="{{ $progressWidth }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="text-muted small">
                                Tanggal Turnamen: {{ \Carbon\Carbon::parse($registration->tournament->registration_start ?? 'N/A')->format('d M Y') }} - {{ \Carbon\Carbon::parse($registration->tournament->registration_end ?? 'N/A')->format('d M Y') }} | Status Pendaftaran: {{ ucfirst($registration->status ?? 'N/A') }}
                                @if ($registration->rejection_reason)
                                    <br>Alasan Penolakan: {{ $registration->rejection_reason }}
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Other tabs content here... --}}
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}?v=1.0">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Your JavaScript code here
});
</script>
@endpush
