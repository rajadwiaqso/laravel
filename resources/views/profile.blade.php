{{-- filepath: /home/rajadwiaqso/Desktop/market 4.0/resources/views/profile.blade.php --}}
@extends('layout')
@section('konten')
    <div class="container mt-5">
        <h2 class="text-center mb-4" style="font-family: 'Playfair Display', serif; font-weight: bold;">Profil Saya</h2>
        <hr class="mb-4">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('failed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('failed') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Informasi Dasar -->
            <div class="col-md-6">
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-primary text-white text-center">
                        <i class="bi bi-person-circle fs-4"></i> Informasi Dasar
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Nama:</strong> {{ Auth::user()->name }}</p>
                        <p class="mb-2"><strong>Email:</strong> {{ Auth::user()->email }}</p>
                        <p class="mb-2"><strong>Role:</strong> {{ Auth::user()->role }}</p>
                        <p class="mb-0"><strong>Bergabung Sejak:</strong> {{ Auth::user()->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Ubah Nama -->
            <div class="col-md-6">
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-success text-white text-center">
                        <i class="bi bi-pencil-square fs-4"></i> Ubah Nama
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.post', Auth::user()) }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Baru:</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success w-100">Simpan Perubahan Nama</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ubah Password -->
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-warning text-white text-center">
                        <i class="bi bi-key fs-4"></i> Ubah Password
                    </div>
                    <div class="card-body">
                        <form action="{{ route('password.update', Auth::user()->id) }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini:</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru:</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="new_password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-2 text-end">
                                <a href="{{route('password.reset.view')}}" class="text-decoration-none">Lupa Password?</a>
                            </div>
                            <button type="submit" class="btn btn-warning w-100">Ubah Password</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="col-md-6">
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-info text-white text-center">
                        <i class="bi bi-geo-alt fs-4"></i> Informasi Tambahan
                    </div>
                    <div class="card-body text-center">
                        <p class="text-muted">Belum ada informasi tambahan yang ditambahkan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection