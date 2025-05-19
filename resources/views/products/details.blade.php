@extends('layout')
@section('style')
    <link rel="stylesheet" href="{{asset('css/rating.css')}}">
    <link rel="stylesheet" href="{{asset('css/product.css')}}">
@endsection
@section('konten')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-6">
                <img src="{{asset('storage/images/' . $product->img)}}" alt="{{$product->produk_name}}" class="img-fluid rounded shadow-sm"> {{-- Gambar Lebih Responsif dan Ada Efek --}}
            </div>
            <div class="col-md-6">
                <h2 class="mb-3">{{$product->produk_name}}</h2>
                <p id="product-id" style="display: none;">{{ $product->id }}</p>
                <h4 class="text-muted mb-3">Rp. {{ number_format($product->price) }}</h4>
                <p class="mb-2">
                    <strong>Stok:</strong>
                    <span id="product-stock">
                        @if ($product->stock > 10)
                            <span id="stock-badge" class="badge bg-success">Tersedia ({{ $product->stock }})</span>
                        @elseif ($product->stock > 0)
                            <span id="stock-badge" class="badge bg-warning text-dark">Hampir Habis ({{ $product->stock }})</span>
                        @else
                            <span id="stock-badge" class="badge bg-danger">Habis</span>
                        @endif
                    </span>
                </p>
                <p class="mb-2">Sold: <span class="badge bg-info">{{$product->sold}}</span></p>
                <p class="mb-4">{{ $product->description }}</p>

                <form action="{{route('buyer.payment', ['category' => $product->category, 'store' => $product->store_name, 'product' => $product->produk_name, 'id_product' => $product->id])}}" method="post">
                    @csrf

                <div class="d-flex align-items-center mb-3">
                    <strong class="me-2">Kuantitas:</strong>
                    <input type="number" class="form-control form-control-sm" value="1"  min="1" max="{{ $product->stock }}" style="width: 70px;" name="kuantitas" > {{-- Opsi Kuantitas --}}
                </div>

                @if ($product->stock > 0)
     
                        
                        <button class="btn btn-primary btn-lg"><i class="bi bi-cart-plus me-2"></i> Beli Sekarang</button>
                   
                @else
                    <button class="btn btn-danger btn-lg disabled"><i class="bi bi-x-octagon me-2"></i> Stok Habis</button>
                @endif
            </form>

                <hr class="my-4">
                <p><strong>Penjual:</strong> <a href="{{route('seller.profile.view', $product->store_name)}}">{{ $product->store_name }}</a></p> {{-- Informasi Toko --}}
            </div>
        </div>

        <div class="mt-5">
            <h3>Ulasan Pembeli</h3>
            <hr>
            @forelse ($rating as $rate)


            @if(!is_int($rate->rating))


                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5>
                            @php
                            $name = $rate->name;
                            $length = strlen($name);
                            if ($length > 2) {
                                $first = substr($name, 0, 1);
                                $last = substr($name, -1);
                                $middle = str_repeat('*', $length - 2);
                                echo $first . $middle . $last;
                            } else {
                                echo $name;
                            }
                        @endphp
                        </h5>
                        <div class="mb-2">


                            @for ($i = 0; $i < $rate->rating['rating']; $i++)
                                <span class="star active">&#9733;</span>
                            @endfor
                            @for ($i = $rate->rating['rating']; $i < 5; $i++)
                                <span class="star">&#9733;</span>
                            @endfor

                        </div>
                        <p class="card-text">{{ $rate->rating['message'] }}</p>
                        <p class="card-text"><small class="text-muted">Diberikan pada {{ $rate->rating['date'] ?? ''}}</small></p>
                    </div>
                </div>

                @endif

            @empty
                <p class="text-muted">Belum ada ulasan untuk produk ini.</p>

            @endforelse
        </div>
    </div>
@endsection

@section('script')
    <script>

        document.addEventListener('DOMContentLoaded', () => {
            console.log(window.Echo);
            
            const productIdElement = document.getElementById('product-id');
            if (productIdElement) {
                const productId = productIdElement.textContent;
                const stockElement = document.getElementById('product-stock');
                const stockBadge = document.getElementById('stock-badge');

                if (stockElement && stockBadge) {
                    console.log(window.Echo.channel('product.51'));
                    
                    window.Echo.channel(`product.${productId}`)
                        .listen('.product.stock.updated', (e) => {
                            console.log('Stok produk diperbarui:', e.newStock);
                            stockElement.textContent = ''; // Kosongkan dulu sebelum memperbarui elemen anak
                            const newStockSpan = document.createElement('span');
                            newStockSpan.id = 'stock-badge';
                            if (e.newStock > 10) {
                                newStockSpan.className = 'badge bg-success';
                                newStockSpan.textContent = `Tersedia (${e.newStock})`;
                            } else if (e.newStock > 0) {
                                newStockSpan.className = 'badge bg-warning text-dark';
                                newStockSpan.textContent = `Hampir Habis (${e.newStock})`;
                            } else {
                                newStockSpan.className = 'badge bg-danger';
                                newStockSpan.textContent = 'Habis';
                            }
                            stockElement.appendChild(newStockSpan);
                        });
                }
            }
        });
    </script>
@endsection