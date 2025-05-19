@extends('layout')

@section('konten')
    <div class="container py-5">
        <h2>Kategori: {{ $category->name }}</h2>
        <hr class="mb-4">

        @if ($products->count() > 0)
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($products as $product)
                    <div class="col">
                        <div class="card shadow-sm h-100">
                            <img src="{{ asset('storage/images/' . $product->img) }}" class="card-img-top" alt="{{ $product->produk_name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->produk_name }}</h5>
                                <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                                <p class="card-text"><small class="text-muted">Kategori: {{ $product->category }}</small></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Rp {{ number_format($product->price) }}</span>
                                    <a href="{{ route('product.details', ['category' => $product->category, 'store' => $product->store_name, 'product' => $product->produk_name, 'id_product' => $product->id]) }}" class="btn btn-sm btn-outline-primary">
                                        Lihat Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @else
            <p class="text-muted">Tidak ada produk dalam kategori ini.</p>
        @endif
    </div>
@endsection