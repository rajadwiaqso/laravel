@extends('layout')
@section('style')
    <link rel="stylesheet" href="{{asset('css/product.css')}}">
@endsection
@section('konten')
    <div class="container mt-5">
        <h2>Daftar Produk Anda</h2>
        <hr class="mb-4">

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @forelse ($list as $product)
                <div class="col">
                    <div class="card shadow-sm h-100">
                        <img src="{{ asset('storage/images/' . $product->img) }}" alt="{{ $product->produk_name }}" class="card-img-top" style="object-fit: cover; height: 200px;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->produk_name }}</h5>
                            <p class="card-text"><small class="text-muted">Rp. {{ number_format($product->price) }} | Stok: {{ $product->stock }}</small></p>
                            <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil-fill me-2"></i> Edit</a>
                                <form action="{{ route('product.delete', $product->id) }}" method="post" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"><i class="bi bi-trash-fill me-2"></i> Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col">
                    <p class="text-muted">Anda belum memiliki produk.</p>
                </div>
            @endforelse
        </div>

        {{-- Paginasi (jika ada) --}}
        {{-- @if ($list->hasPages())
            <div class="mt-4">
                {{ $list->links() }}
            </div>
        @endif --}}
    </div>
@endsection