@extends('layouts.admin')

@section('title', 'Admin - Buat Komunitas Official')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <div>
                            <h2 class="fw-bold mb-1" style="color: #cb2786;">Buat Komunitas <span class="bg-primary text-white px-2 py-1 rounded small fs-6">Official</span></h2>
                            <p class="text-muted">Komunitas yang dibuat oleh admin akan otomatis mendapatkan badge "Official".</p>
                        </div>
                        <a href="{{ route('admin.userpages.komunitas') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;">
                            Batal
                        </a>
                    </div>

                    <form action="{{ route('admin.userpages.komunitas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Community Name -->
                            <div class="col-12">
                                <label class="form-label fw-bold">Nama Komunitas Official</label>
                                <div class="input-group">
                                    <input type="text" name="name" class="form-control py-3 @error('name') is-invalid @enderror" 
                                           placeholder="Contoh: KAMCUP Elite Academy" required value="{{ old('name') }}"
                                           style="border-radius: 10px 0 0 10px;">
                                    <span class="input-group-text bg-light fw-bold text-primary" style="border-radius: 0 10px 10px 0;">[Official]</span>
                                </div>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Jenis Komunitas</label>
                                <select name="category" class="form-select py-3 @error('category') is-invalid @enderror" required
                                        style="border-radius: 10px;">
                                    <option value="" disabled selected>Pilih Cabang Olahraga</option>
                                    <option value="Sepak Bola">Sepak Bola</option>
                                    <option value="Futsal">Futsal</option>
                                    <option value="Bulutangkis">Bulutangkis</option>
                                    <option value="Voli">Voli</button>
                                    <option value="Basket">Basket</option>
                                    <option value="E-Sports">E-Sports</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                @error('category')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status Akses</label>
                                <select name="status" class="form-select py-3 @error('status') is-invalid @enderror" required
                                        style="border-radius: 10px;">
                                    <option value="public">Publik (Open Membership)</option>
                                    <option value="private">Privat (Moderated)</option>
                                </select>
                                @error('status')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label class="form-label fw-bold">Deskripsi Komunitas</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" 
                                          placeholder="Tuliskan deskripsi lengkap mengenai komunitas official ini..."
                                          style="border-radius: 10px;">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image -->
                            <div class="col-12">
                                <label class="form-label fw-bold">Cover / Logo (Opsional)</label>
                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                       style="border-radius: 10px;">
                                @error('image')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="col-12 mt-5">
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm" style="border-radius: 12px;">
                                    Publish Komunitas Official <i class="fas fa-paper-plane ms-2"></i>
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
