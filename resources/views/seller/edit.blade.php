@extends('layout')
@section('style')
    <link rel="stylesheet" href="{{asset('css/product.css')}}">
@endsection
@section('konten')
    <div class="container mt-5">
        <h2>Edit Produk</h2>
        <hr class="mb-4">

        <div class="card shadow-sm p-4">
            <form action="{{ route('product.edit.submit', $product->id) }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk:</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->produk_name) }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jika ada kategori --}}
                {{-- <div class="mb-3">
                    <label for="category" class="form-label">Kategori:</label>
                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                        <option value="" disabled>Pilih Kategori</option>
                        <option value="pubg" {{ old('category', $product->category) == 'pubg' ? 'selected' : '' }}>Pubg</option>
                        <option value="mobile-legends" {{ old('category', $product->category) == 'mobile-legends' ? 'selected' : '' }}>Mobile Legends</option>
                        <option value="free-fire" {{ old('category', $product->category) == 'free-fire' ? 'selected' : '' }}>Free Fire</option>
                        <option value="roblox" {{ old('category', $product->category) == 'roblox' ? 'selected' : '' }}>Roblox</option>
                        {{-- Tambahkan opsi kategori lain dari database jika ada --}}
                    {{-- </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> --}}

                <div class="mb-3">
                    <label for="price" class="form-label">Harga:</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label">Stok:</label>
                    <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="img" class="form-label">Gambar Produk:</label>
                    <div class="mb-2">
                        <img src="{{ asset('storage/images/' . $product->img) }}" alt="{{ $product->produk_name }}" style="max-height: 150px;">
                    </div>
                    <input type="file" class="form-control @error('img') is-invalid @enderror" id="img" name="img" accept="image/*">
                    <div class="form-text">Kosongkan jika tidak ingin mengubah gambar. Format file: JPG, JPEG, PNG. Ukuran maksimal: [sebutkan ukuran jika ada].</div>
                    @error('img')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi:</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
@endsection