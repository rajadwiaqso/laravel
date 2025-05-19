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
                    <label for="fullname" class="form-label">Nama Panjang:</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" required>
                    @error('fullname')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Toko:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                {{-- nomer telpon --}}
                
                <div class="mb-3">
                    <label for="phone" class="form-label">Nomor Telepon:</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                    @error('phone')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- punya ktp atau tidak --}}
                <div class="mb-3">
    <label for="ktp" class="form-label">Apakah Anda Memiliki KTP?</label>
    <select class="form-select" id="ktp" name="ktp" required>
        <option value="" disabled selected>Pilih</option>
        <option value="1">Ya</option>
        <option value="0">Tidak</option>
    </select>
    @error('ktp')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

{{-- jika memiliki ktp maka akan muncul input nik dan upload ktp --}}
<div id="ktp-fields" style="display: none;">
    <div class="mb-3">
        <label for="nik" class="form-label">NIK:</label>
        <input type="text" class="form-control" id="nik" name="nik">
        @error('nik')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="img" class="form-label">Upload KTP/Identitas:</label>
        <input type="file" class="form-control" id="img" name="img" accept="image/*">
        <small class="form-text text-muted">Format file: JPG, JPEG, PNG.</small>
        @error('img')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

                <div class="mb-3">
                    <label for="message" class="form-label">Pesan Tambahan (Opsional):</label>
                    <textarea class="form-control" id="message" name="message" rows="5"></textarea>
                    @error('message')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Kirim Permohonan</button>
            </form>
        @endif
    </div>
@endsection

@section('script')
   <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ktpSelect = document.getElementById('ktp');
        const ktpFields = document.getElementById('ktp-fields');
        const nikInput = document.getElementById('nik');
        const imgInput = document.getElementById('img');

        function toggleKtpFields() {
            if (ktpSelect.value === "1") {
                ktpFields.style.display = 'block';
                nikInput.required = true;
                imgInput.required = true;
            } else {
                ktpFields.style.display = 'none';
                nikInput.required = false;
                imgInput.required = false;
            }
        }

        ktpSelect.addEventListener('change', toggleKtpFields);

        
        toggleKtpFields();
    });
</script>
@endsection