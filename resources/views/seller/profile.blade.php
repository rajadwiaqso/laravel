
@extends('layout')

@section('konten')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Profil Toko</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if ($seller->profile_picture)
                            <img src="{{ asset('storage/images/profile/' . $seller->profile_picture) }}" alt="Foto Profil" class="rounded-circle" style="width: 100px; height: 100px;">
                        @else
                            <img src="{{ asset('images/no-image.png') }}" alt="No Image" class="rounded-circle" style="width: 100px; height: 100px;">
                        @endif
                    </div>
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th>Pemilik</th>
                            <td>{{ $seller->name }}</td>
                        </tr>   
                        <tr>
                            <th>Total Produk</th>
                            <td>{{ isset($products) ? $products->count() : 0 }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Daftar Produk</h5>
                </div>
                <div class="card-body">
                    @if(isset($products) && $products->count())
                        <div class="row">
                            @foreach($products as $product)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        @if($product->img)
                                            <img src="{{ asset('storage/images/products/' . $product->img) }}" class="card-img-top" alt="{{ $product->name }}">
                                        @else
                                            <img src="{{ asset('images/no-image.png') }}" class="card-img-top" alt="No Image">
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $product->produk_name }}</h6>
                                            <p class="card-text mb-1">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                            <p class="card-text text-muted" style="font-size: 0.9em;">Stok: {{ $product->stock }}</p>
                                              <a href="{{ route('product.details', ['category' => $product->category, 'store' => $product->store_name, 'product' => $product->produk_name, 'id_product' => $product->id]) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Belum ada produk di toko ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection