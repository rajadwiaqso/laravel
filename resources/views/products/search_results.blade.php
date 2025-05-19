@extends('layout')

@section('konten')
    <div class="container py-5">
        <h2>Hasil Pencarian untuk "{{ $query }}"</h2>
        <hr class="mb-4">

        @if ($products->count() > 0)
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($products as $product)
                @if($product->stock != 0)
                    <div class="col">
                        <div class="card shadow-sm h-100">
                            <img src="{{ asset('storage/images/' . $product->img) }}" class="card-img-top" alt="{{ $product->produk_name }}" style="object-fit: cover; height: 200px;"> {{-- Gambar Lebih Proporsional --}}
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->produk_name }}</h5>
                                <p class="card-text"><small class="text-muted">Rp. {{ number_format($product->price) }} | Stok: <span id="stock-{{ $product->id }}">{{ $product->stock }}</span> | Sold: {{$product->sold}}</small></p>
                                <p class="card-text">{{ Str::limit($product->description, 100) }}</p> {{-- Deskripsi Terpotong --}}

                                @if(Auth::check() && Auth::user()->role == 'seller')

                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil-fill me-2"></i> Edit</a>
                                    <form action="{{ route('product.delete', $product->id) }}" method="post" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"><i class="bi bi-trash-fill me-2"></i> Hapus</button>
                                    </form>
                                </div>
                                @else
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('product.details', ['category' => $product->category, 'store' => $product->store_name, 'product' => $product->produk_name, 'id_product' => $product->id]) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                                    {{-- Tombol Tambah ke Keranjang (bisa ditambahkan jika perlu) --}}
                                    {{-- <button class="btn btn-sm btn-success"><i class="bi bi-cart-plus"></i> Beli</button> --}}
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @else
            <p class="text-muted">Tidak ada produk yang ditemukan untuk "{{ $query }}".</p>
        @endif
    </div>
@endsection