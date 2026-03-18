@extends('layouts.master_nav')

@section('title', 'Komunitas')

@section('content')
<div class="container py-5">
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
        <a href="{{ route('user2026.komunitas') }}" class="btn btn-category {{ !$category || $category == 'Semua' ? 'active' : '' }}">Semua</a>
        @foreach(['Voli', 'Futsal', 'Bulutangkis', 'Tenis meja', 'Kasti'] as $cat)
            <a href="{{ route('user2026.komunitas', ['category' => $cat]) }}" 
               class="btn btn-category {{ $category == $cat ? 'active' : '' }} text-decoration-none">{{ $cat }}</a>
        @endforeach
    </div>

    @php
        $joined = $communities->filter(fn($c) => $c->members->contains(Auth::id()));
        $notJoined = $communities->filter(fn($c) => !$c->members->contains(Auth::id()));
    @endphp

    <!-- User's Joined Communities -->
    @if($joined->isNotEmpty())
    <div class="mb-5">
        <h4 class="fw-bold mb-4 scroll-reveal" style="color: #00617a;">Komunitas Saya</h4>
        <div class="row g-4 mb-4">
            @foreach($joined as $index => $community)
                @include('User2026.partials.community_card', ['community' => $community, 'index' => $index, 'isJoined' => true])
            @endforeach
        </div>
    </div>
    <hr class="mb-5 opacity-10">
    @endif

    <!-- Other Communities -->
    <div class="mb-5">
        <h4 class="fw-bold mb-4 scroll-reveal" style="color: #cb2786;">{{ $joined->isNotEmpty() ? 'Cari Komunitas Lain' : 'Temukan Komunitas' }}</h4>
        <div class="row g-4">
            @forelse($notJoined as $index => $community)
                @include('User2026.partials.community_card', ['community' => $community, 'index' => $index, 'isJoined' => false])
            @empty
                <div class="col-12 text-center py-5 scroll-reveal">
                    <i class="fas fa-search fa-3x mb-3 text-muted opacity-25"></i>
                    <h5 class="text-muted">Tidak ada komunitas lain yang ditemukan di kategori ini.</h5>
                </div>
            @endforelse
        </div>
    </div>

    @if($communities->isEmpty())
    <div class="col-12 text-center py-5 scroll-reveal">
        <i class="fas fa-users fa-4x mb-3 text-muted opacity-25"></i>
        <h4 class="text-muted">Belum ada komunitas yang tersedia.</h4>
        <p>Jadilah yang pertama untuk membangun komunitas di KAMCUP!</p>
    </div>
    @endif
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
