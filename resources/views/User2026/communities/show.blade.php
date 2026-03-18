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
                    <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                        <span class="badge bg-primary mb-2">{{ $community->category }}</span>
                        <h1 class="text-white fw-bold mb-0">{{ $community->name }}</h1>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-auto text-muted small">
                            Created by <span class="fw-bold">{{ $community->creator->name }}</span> 
                            &bull; {{ $community->created_at->format('d M Y') }}
                        </div>
                    </div>
                    
                    <h5 class="fw-bold mb-3">Tentang Komunitas</h5>
                    <p class="text-secondary" style="line-height: 1.8;">
                        {{ $community->description ?? 'Belum ada deskripsi untuk komunitas ini.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Sidebar / Action Area -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-4">Keanggotaan</h5>
                    <div class="d-flex justify-content-center gap-4 mb-4">
                        <div>
                            <div class="h3 fw-bold mb-0 text-primary">{{ $community->members->count() }}</div>
                            <div class="small text-muted">Anggota</div>
                        </div>
                        <div>
                            <div class="h3 fw-bold mb-0 text-primary">0</div>
                            <div class="small text-muted">Postingan</div>
                        </div>
                    </div>

                    @if($isJoined)
                        <div class="alert alert-success border-0 small mb-3">
                            <i class="fas fa-check-circle me-1"></i> Anda sudah bergabung di komunitas ini.
                        </div>
                        <button class="btn btn-outline-danger w-100 rounded-pill py-2">
                            Keluar Komunitas
                        </button>
                    @else
                        <button class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm mb-3">
                            Gabung Sekarang <i class="fas fa-plus ms-2"></i>
                        </button>
                        <p class="small text-muted mb-0">
                            Status: <span class="fw-bold">{{ ucfirst($community->status) }}</span>
                        </p>
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
@endsection
