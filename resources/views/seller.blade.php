@extends('layout')
@section('konten')
    <div class="container my-5">
        <h2>Formulir Pendaftaran Penjual</h2>
        <hr class="mb-4">

        @if ($data == 1)
            <div class="alert alert-info" role="alert">
                Anda sudah mengirimkan formulir ini. Mohon tunggu 1-3 hari kerja untuk proses verifikasi.
            </div>
        @else
            <form action="{{ route('seller.post') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Toko:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Toko:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="img" class="form-label">Upload KTP/Identitas:</label>
                    <input type="file" class="form-control" id="img" name="img" accept="image/*" required>
                    <small class="form-text text-muted">Format file: JPG, JPEG, PNG. Ukuran maksimal: [sebutkan ukuran jika ada].</small>
                    @error('img')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Pesan Tambahan (Opsional):</label>
                    <textarea class="form-control" id="message" name="message" rows="5"></textarea>
                    @error('message')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Submit Permohonan</button>
            </form>
        @endif
    </div>
@endsection