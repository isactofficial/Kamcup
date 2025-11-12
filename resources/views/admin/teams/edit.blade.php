@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    
    {{-- Tombol Kembali --}}
    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('admin.teams.index') }}" class="btn px-4 py-2"
            style="background-color: #F0F5FF; color: #5B93FF; border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

    {{-- Form Edit --}}
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-header py-3" style="background-color: var(--bg-light);">
            <h5 class="mb-0 fw-bold">Edit Tim: {{ $team->name }}</h5>
        </div>
        <div class="card-body p-4">
            
            <form action="{{ route('admin.teams.update', $team->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row mb-4">
                    {{-- Kolom Kiri - Info Tim --}}
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="name" class="form-label text-secondary fw-medium">Nama Tim</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $team->name) }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="manager_name" class="form-label text-secondary fw-medium">Nama Manajer</label>
                                <input type="text" class="form-control" id="manager_name" name="manager_name" value="{{ old('manager_name', $team->manager_name) }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="contact" class="form-label text-secondary fw-medium">Kontak</label>
                                <input type="text" class="form-control" id="contact" name="contact" value="{{ old('contact', $team->contact) }}" required>
                            </div>
                            
                            <div class="col-12">
                                <label for="location" class="form-label text-secondary fw-medium">Lokasi</label>
                                <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $team->location) }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="gender_category" class="form-label text-secondary fw-medium">Kategori Gender</label>
                                <select class="form-select" id="gender_category" name="gender_category" required>
                                    <option value="male" {{ old('gender_category', $team->gender_category) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender_category', $team->gender_category) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="mixed" {{ old('gender_category', $team->gender_category) == 'mixed' ? 'selected' : '' }}>Mixed</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="member_count" class="form-label text-secondary fw-medium">Jumlah Anggota (Slot)</label>
                                <input type="number" class="form-control" id="member_count" name="member_count" value="{{ old('member_count', $team->member_count) }}" min="1" max="10" required>
                            </div>
                            
                            <div class="col-12">
                                <label for="description" class="form-label text-secondary fw-medium">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $team->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan - Status & Logo --}}
                    <div class="col-md-4">
                        {{-- KOTAK STATUS (Persetujuan) --}}
                        <div class="card mb-4 border-primary">
                            <div class="card-header bg-primary text-white fw-bold">
                                Status Persetujuan
                            </div>
                            <div class="card-body">
                                <label for="status" class="form-label fw-medium">Ubah Status Tim</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending" {{ old('status', $team->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ old('status', $team->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ old('status', $team->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                <div class="form-text">Setujui atau tolak pendaftaran tim ini.</div>
                            </div>
                        </div>

                        {{-- Logo Tim --}}
                        <label class="form-label text-secondary fw-medium">Logo Tim</label>
                        <div class="position-relative border rounded-3 p-3 text-center" style="min-height: 240px;">
                            <img id="logoPreview" 
                                 src="{{ $team->logo ? asset('storage/' . $team->logo) : asset('assets/img/team-placeholder.png') }}" 
                                 alt="Logo Preview" 
                                 class="img-fluid rounded mb-3" 
                                 style="max-height: 200px; object-fit: contain;">
                            
                            <input type="file" id="logo" name="logo" class="form-control" 
                                   onchange="document.getElementById('logoPreview').src = window.URL.createObjectURL(this.files[0])">
                            <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah logo.</small>
                        </div>
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5" style="background-color: var(--primary-color);">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection