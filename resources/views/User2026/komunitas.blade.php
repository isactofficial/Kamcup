@extends('layouts.master_nav')

@section('title', 'Komunitas')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="row mb-5 mt-4">
        <div class="col-lg-7 mx-auto text-center scroll-reveal">
            <span class="badge px-3 py-2 mb-3" style="background-color: #cb27861A; color: #cb2786; border-radius: 30px; font-weight: 600; letter-spacing: 1px;">KONSOLIDASI & KOLABORASI</span>
            <h1 class="display-4 fw-bold mb-3" style="color: #cb2786;">Komunitas <span style="color: #00617a;">KAMCUP</span></h1>
            <p class="text-muted fs-5">Temukan teman baru, bagikan minatmu, dan berkembang bersama ribuan anggota lainnya di berbagai komunitas favorit.</p>
        </div>
    </div>

    <!-- Search & Quick Navigation -->
    <div class="row mb-4 g-3 align-items-center scroll-reveal">
        <div class="col-md-9">
            <div class="position-relative">
                <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-4 text-muted" style="z-index: 10;"></i>
                <input type="text" class="form-control ps-5 py-3 border-0 shadow-sm" 
                       placeholder="Cari komunitas berdasarkan nama atau kategori..." 
                       style="border-radius: 15px; font-size: 1.1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;">
            </div>
        </div>
        <div class="col-md-3 text-md-end">
            <a href="{{ route('user2026.komunitas.create') }}" class="btn px-4 py-3 w-100 d-inline-flex align-items-center justify-content-center" 
               style="background-color: #cb2786; color: #fff; border-radius: 15px; font-weight: 600; box-shadow: 0 4px 15px rgba(203,39,134,0.2);">
                <i class="fas fa-plus me-2"></i>Buat Komunitas
            </a>
        </div>
    </div>

    <!-- Category Pills -->
    <div class="d-flex flex-nowrap gap-2 mb-5 scroll-reveal justify-content-start justify-content-md-center overflow-auto pb-3 no-scrollbar">
        <button class="btn btn-category active">Semua</button>
        <button class="btn btn-category">Sepak Bola</button>
        <button class="btn btn-category">Futsal</button>
        <button class="btn btn-category">Bulutangkis</button>
        <button class="btn btn-category">Voli</button>
        <button class="btn btn-category">Basket</button>
        <button class="btn btn-category">E-Sports</button>
    </div>

    <!-- Community Grid -->
    <div class="row g-4 mb-5">
        @forelse($communities as $index => $community)
        <div class="col-md-6 col-lg-4 scroll-reveal" style="transition-delay: {{ $index * 0.05 }}s;">
            <div class="card h-100 profile-info-card border-0 community-item shadow-sm position-relative">
                <div class="card-body p-4 d-flex flex-column" style="z-index: 2;">
                    @if($community->is_official)
                        <div class="position-absolute top-0 end-0 mt-3 me-3">
                            <span class="badge px-3" style="background-color: #00617a; color: #fff; font-size: 0.7rem; border-radius: 8px;">
                                Official
                            </span>
                        </div>
                    @elseif($community->status == 'private')
                         <div class="position-absolute top-0 end-0 mt-3 me-3">
                            <span class="badge px-3" style="background-color: #f4b704; color: #212529; font-size: 0.7rem; border-radius: 8px;">
                                Private
                            </span>
                        </div>
                    @endif

                    <div class="d-flex align-items-center mb-4">
                        <div class="community-logo d-flex align-items-center justify-content-center shadow-sm" 
                             style="background-color: #cb278615; width: 65px; height: 65px; border-radius: 8px; object-fit: contain;">
                            @if($community->image)
                                <img src="{{ asset('storage/' . $community->image) }}" class="w-100 h-100" style="object-fit: contain; border-radius: 16px;">
                            @else
                                <i class="fas fa-users" style="color: #cb2786; font-size: 1.5rem;"></i>
                            @endif
                        </div>
                        <div class="ms-3">
                            <span class="text-uppercase small fw-bold tracking-wider" style="color: #cb2786; letter-spacing: 1px; font-size: 0.75rem;">
                                {{ $community->category }}
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">{{ $community->name }} {{ $community->is_official ? '[Official]' : '' }}</h5>
                            <small class="text-muted">by {{ $community->creator->name }}</small>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-muted small mb-0 line-clamp-2">{{ $community->description ?? 'Deskripsi singkat mengenai komunitas ' . $community->name . ' yang inspiratif and aktif.' }}</p>
                    </div>

                    <div class="mt-auto pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="avatar-stack">
                                    <div class="avatar-pill" style="background-color: #cb278630;"></div>
                                    <div class="avatar-pill" style="background-color: #00617a30;"></div>
                                    <div class="avatar-pill" style="background-color: #f4b70430;"></div>
                                </div>
                                <span class="ms-2 text-muted fw-semibold small">{{ $community->members->count() }} Member</span>
                            </div>
                            <button class="btn join-btn px-4 py-2" style="background-color: #cb2786; color: #fff; border-radius: 10px; font-weight: 600; font-size: 0.9rem;">
                                Join <i class="fas fa-plus ms-1" style="font-size: 0.7rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-users fa-4x mb-3 text-muted opacity-25"></i>
            <h4 class="text-muted">Belum ada komunitas yang tersedia.</h4>
            <p>Jadilah yang pertama untuk membangun komunitas di KAMCUP!</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .btn-category {
        border: 1px solid #dee2e6;
        background: #fff;
        color: #495057;
        border-radius: 12px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        font-size: 0.95rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        white-space: nowrap;
    }
    
    .btn-category:hover, .btn-category.active {
        background-color: #cb2786;
        color: #fff;
        border-color: #cb2786;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(203, 39, 134, 0.2);
    }
    
    .community-item {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        overflow: hidden;
    }
    
    .community-item:hover {
        transform: translateY(-10px) scale(1.02);
    }
    
    .avatar-stack {
        display: flex;
        align-items: center;
    }
    
    .avatar-pill {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background-color: #e9ecef;
        margin-left: -10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .avatar-pill:first-child {
        margin-left: 0;
    }
    
    .join-btn {
        transition: all 0.3s;
        border: none;
    }
    
    .join-btn:hover {
        opacity: 0.9;
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }

    .create-card {
        transition: all 0.3s;
    }

    .create-card:hover {
        background-color: #fff !important;
        border-color: #cb2786 !important;
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    
    .create-card:hover h5 {
        color: #cb2786;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Scroll Reveal Fallback */
    .scroll-reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94), transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .scroll-reveal.revealed {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));
    });
</script>
@endpush
