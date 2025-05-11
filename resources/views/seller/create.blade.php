@extends('layout')
@section('konten')
    <div class="container mt-5">
        <h2>Buat Produk Baru</h2>
        <hr class="mb-4">

        <div class="card shadow-sm p-4">
            <form action="{{ route('product.create.submit') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="product_name" class="form-label">Nama Produk:</label>
                    <input type="text" class="form-control @error('product_name') is-invalid @enderror" id="product_name" name="product_name" value="{{ old('product_name') }}" required autofocus>
                    @error('product_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label">Kategori:</label>
                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                        <option value="" selected disabled>Pilih Kategori</option>
                        <option value="pubg" {{ old('category') == 'pubg' ? 'selected' : '' }}>Pubg</option>
                        <option value="mobile-legends" {{ old('category') == 'mobile-legends' ? 'selected' : '' }}>Mobile Legends</option>
                        <option value="free-fire" {{ old('category') == 'free-fire' ? 'selected' : '' }}>Free Fire</option>
                        <option value="roblox" {{ old('category') == 'roblox' ? 'selected' : '' }}>Roblox</option>
                        {{-- Tambahkan opsi kategori lain dari database jika ada --}}
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Harga:</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label">Stok:</label>
                    <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock') }}" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Produk:</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" required>
                    <div class="form-text">Format file: JPG, JPEG, PNG. Ukuran maksimal: [sebutkan ukuran jika ada].</div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi:</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan Produk</button>
            </form>
        </div>
    </div>
@endsection