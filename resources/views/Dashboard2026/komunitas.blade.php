@extends('layouts.admin')

@section('title', 'Komunitas')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0" style="color: #cb2786;">Kelola Komunitas</h1>
        <a href="{{ route('admin.userpages.komunitas.create') }}" class="btn btn-primary px-4 py-2 fw-bold" style="border-radius: 10px;">
            <i class="fas fa-plus me-2"></i>Buat Komunitas Official
        </a>
    </div>
    
    <!-- Rest of the admin community list logic can go here -->
</div>
@endsection

