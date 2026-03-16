@extends('layouts.master_nav')

@section('title', 'Teman')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center scroll-reveal">
            <h1 class="display-4 fw-bold mb-4" style="color: #f4b704;">Teman</h1>
            <p class="lead mb-5 text-muted">Halaman Teman KAMCUP</p>
            
            <a href="{{ route('profile.index') }}" class="btn btn-lg px-4" style="background-color: #00617a; color: #fff; border-radius: 8px; font-weight: 600;">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Profile
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

