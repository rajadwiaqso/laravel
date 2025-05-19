{{-- filepath: /home/rajadwiaqso/Desktop/market 4.0/resources/views/index.blade.php --}}
@extends('layout')

@section('style')
    <link rel="stylesheet" href="{{asset('css/index.css')}}">
    <link rel="stylesheet" href="{{asset('css/product-details.css')}}">

    <style>
        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('images/hero-bg.jpg') }}') no-repeat center center/cover;
            color: white;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.8);
            padding: 100px 0;
            height: 50vh;
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: bold;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 30px;
        }

        .hero .btn-primary {
            background-color: #5a4fcf;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 30px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        .hero .btn-primary:hover {
            background-color: #4a3dbf;
        }

        /* Kategori Pilihan */
        .card.item {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .card.item:hover{
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .card.item:hover i{
        color: white
        }


        .card-body i {
            color: #5a4fcf;
        }
     

        .card-body h5 {
            font-weight: bold;
        }

        /* Produk Unggulan */
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .card .btn-outline-primary {
            border-color: #5a4fcf;
            color: #5a4fcf;
            border-radius: 20px;
        }

        .card .btn-outline-primary:hover {
            background-color: #5a4fcf;
            color: white;
        }

        /* Autocomplete */
        #autocomplete-results {
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            
        }

        .autocomplete-item {
            transition: background-color 0.2s ease;
            padding: 10px;
            font-size: 0.9rem;
        }

        .autocomplete-item:hover {
            background-color: #f1f1f1;
        }

           .form-control:focus {
            border-color: #5a4fcf;
            box-shadow: 0 0 8px rgba(90, 79, 207, 0.5);
        }   
    </style>
@endsection

@section('konten')

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    
@endif

    <section class="hero text-center">
        <div class="container">
            <h1 class="mb-4">Selamat Datang, {{ Auth::user()->name ?? ''}} di
{{config('app.name')}}!</h1>
            <p class="lead">Temukan berbagai produk berkualitas dengan harga terbaik.</p>
            <div class="input-group mt-4 shadow-sm justify-content-center" style="max-width: 600px;">
                <input type="search" class="form-control search-input" id="search-input" placeholder="Cari produk atau kategori...">
                <button class="btn btn-primary" type="button" id="search-button">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
            <div id="autocomplete-results" class="list-group mt-2"></div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5" style="font-family: 'Playfair Display', serif; font-weight: bold;">Kategori Pilihan</h2>
            <div class="row row-cols-2 row-cols-md-4 g-4">
                @foreach ($categories as $category)
                <div class="col">
                    <a href="{{ route('products.category', $category->slug) }}" class="text-decoration-none text-dark">
                        <div class="card shadow-sm h-100 item">
                            <div class="card-body text-center">
                                <i class="bi bi-tag fs-3 mb-3"></i>
                                <h5>{{ $category->name }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5" style="font-family: 'Playfair Display', serif; font-weight: bold;">Produk Unggulan</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($featuredProducts as $product)
                @if($product->stock != 0)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('storage/images/' . $product->img) }}" class="card-img-top" alt="{{ $product->produk_name }}" style="object-fit: cover; height: 200px;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->produk_name }}</h5>
                            <p class="card-text"><small class="text-muted">Rp. {{ number_format($product->price) }} | Stok: <span id="stock-{{ $product->id }}">{{ $product->stock }}</span> | Sold: {{$product->sold}}</small></p>
                            <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('product.details', ['category' => $product->category, 'store' => $product->store_name, 'product' => $product->produk_name, 'id_product' => $product->id]) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </section>
   
    
    {{-- <div class="container my-5 text-center">
        <a href="{{ route('buyer.orders') }}" class="btn btn-primary btn-lg" style="border-radius: 30px;">Top Up Resmi</a>
    </div> --}}
    
@endsection

@section('script')
    <script>

document.addEventListener('DOMContentLoaded', () => {
    @foreach ($featuredProducts as $product)
    window.Echo.channel(`product.{{ $product->id }}`)
        .listen('.product.stock.updated', (e) => {
            console.log(`Stok produk ${e.productId} diperbarui menjadi: ${e.newStock}`);
            if (e.productId == {{ $product->id }}) {
                const stockElement = document.getElementById(`stock-{{ $product->id }}`);
                if (stockElement) {
                    stockElement.textContent = e.newStock;
                }
            }
        });
    @endforeach
})






        const searchInput = document.getElementById('search-input');
        const autocompleteResults = document.getElementById('autocomplete-results');
        const searchButton = document.getElementById('search-button');

        searchInput.addEventListener('input', function() {
            const query = this.value;
            autocompleteResults.innerHTML = ''; // Clear previous results

            if (query.length >= 2) { // Start searching after at least 2 characters
                fetch(`/api/search/autocomplete?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        
                        if (data.products.length > 0) {
                            data.products.forEach(product => {
                                const item = document.createElement('a');
                                item.classList.add('list-group-item', 'list-group-item-action', 'autocomplete-item');
                                item.textContent = `Produk: ${product.produk_name} | Category: ${product.category}`;
                                item.href = `/products/search?query=${product.produk_name}`; // Redirect to search results
                                autocompleteResults.appendChild(item);
                            });
                        }
                        if (data.categories.length > 0) {
                            data.categories.forEach(category => {
                                const item = document.createElement('a');
                                item.classList.add('list-group-item', 'list-group-item-action', 'autocomplete-item');
                                item.textContent = `Kategori: ${category.name}`;
                                item.href = `/products/category/${category.slug}`; // Redirect to category page
                                autocompleteResults.appendChild(item);
                            });
                        }
                        if (data.products.length === 0 && data.categories.length === 0) {
                            const item = document.createElement('div');
                            item.classList.add('list-group-item', 'autocomplete-item');
                            item.textContent = 'Tidak ada hasil ditemukan';
                            autocompleteResults.appendChild(item);
                        }
                    });
            }
        });

        // Handle search button click (optional, if you want to trigger search this way too)
        searchButton.addEventListener('click', function() {
            const query = searchInput.value;
            window.location.href = `/products/search?query=${query}`;
        });

        // Close autocomplete when clicking outside the input and results
        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !autocompleteResults.contains(event.target)) {
                autocompleteResults.innerHTML = '';
            }
        });
    </script>
@endsection