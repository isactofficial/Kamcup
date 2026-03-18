@extends('layouts.master_nav')

@section('title', 'Buat Komunitas')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 scroll-reveal">
            <div class="card profile-info-card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('user2026.komunitas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <!-- Community Name -->
                            <div class="col-12">
                                <label class="form-label fw-bold" style="color: #495057;">Nama Komunitas</label>
                                <input type="text" name="name" class="form-control py-3 @error('name') is-invalid @enderror" 
                                       placeholder="Contoh: Smash Masters Jakarta" required value="{{ old('name') }}"
                                       style="border-radius: 12px; background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: #495057;">Jenis Komunitas</label>
                                <select name="category" class="form-select py-3 @error('category') is-invalid @enderror" required
                                        style="border-radius: 12px; background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                    <option value="" disabled selected>Pilih Cabang Olahraga</option>
                                    <option value="Futsal">Futsal</option>
                                    <option value="Bulutangkis">Bulutangkis</option>
                                    <option value="Voli">Voli</option>
                                    <option value="Tenis meja">Tenis meja</option>
                                    <option value="Kasti">Kasti</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: #495057;">Status Komunitas</label>
                                <select name="status" class="form-select py-3 @error('status') is-invalid @enderror" required
                                        style="border-radius: 12px; background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                    <option value="public">Publik (Siapa saja bisa join)</option>
                                    <option value="private">Privat (Perlu persetujuan admin komunitas)</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label class="form-label fw-bold" style="color: #495057;">Deskripsi Komunitas</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" 
                                          placeholder="Jelaskan visi, misi, atau kegiatan komunitasmu..."
                                          style="border-radius: 12px; background-color: #f8f9fa; border: 1px solid #dee2e6;">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image -->
                            <div class="col-12">
                                <label class="form-label fw-bold" style="color: #495057;">Foto Profil Komunitas (Opsional)</label>
                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                       style="border-radius: 8px; background-color: #f8f9fa;">
                                <div class="form-text small">Rekomendasi ukuran: 500x500px, Maks 2MB.</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="col-12 mt-5">
                                <button type="submit" class="btn btn-lg w-100 py-3 fw-bold" 
                                        style="background-color: #cb2786; color: #fff; border-radius: 15px; box-shadow: 0 4px 15px rgba(203,39,134,0.3);">
                                    Buat Komunitas Sekarang <i class="fas fa-check-circle ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<style>
    .form-control:focus, .form-select:focus {
        border-color: #cb2786 !important;
        box-shadow: 0 0 0 0.25rem rgba(203, 39, 134, 0.1) !important;
    }
</style>
@endpush
