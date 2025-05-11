{{-- filepath: /home/rajadwiaqso/Desktop/market 4.0/resources/views/products.blade.php --}}
@extends('layout')
@section('style')
    <link rel="stylesheet" href="{{asset('css/product.css')}}">
    <style>
        .card:hover {
            transform: scale(1.03);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .card .btn-outline-primary:hover {
            background-color: #6c63ff;
            color: white;
        }
    </style>
@endsection
@section('konten')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Kategori: {{ $category ?? 'Semua Produk' }}</h2> {{-- Judul Kategori --}}
            {{-- <form action="{{ route('products.filter') }}" method="GET" class="d-flex">
                <select name="category" class="form-select me-2" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form> --}}
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"> {{-- Grid yang Lebih Responsif --}}
            @forelse ($products as $product)
                @if ($product->stock > 0)
                    <div class="col">
                        <div class="card h-100 shadow-sm"> {{-- Menggunakan Card Bootstrap --}}
                            <img src="{{ asset('storage/images/' . $product->img) }}" class="card-img-top" alt="{{ $product->produk_name }}" style="object-fit: cover; height: 200px;"> {{-- Gambar Lebih Proporsional --}}
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->produk_name }}</h5>
                                <p class="card-text"><small class="text-muted">Rp. {{ number_format($product->price) }} | Stok: {{ $product->stock }} | Sold: {{$product->sold}}</small></p> {{-- Info Lebih Ringkas --}}
                                <p class="card-text">{{ Str::limit($product->description, 100) }}</p> {{-- Deskripsi Terpotong --}}
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('product.details', ['category' => $product->category, 'store' => $product->store_name, 'product' => $product->produk_name, 'id_product' => $product->id]) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                                    {{-- Tombol Tambah ke Keranjang --}}
                                    {{-- <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-cart-plus"></i> Beli</button>
                                    </form> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col">
                    <p class="text-muted">Tidak ada produk di kategori ini.</p>
                </div>
            @endforelse
        </div>

        <!-- Paginasi -->
        {{-- @if ($products->hasPages())
            <div class="mt-4">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $products->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach
                        <li class="page-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        @endif --}}
    </div>
@endsection