@extends('layout')
@section('style')
    <link rel="stylesheet" href="{{asset('css/product.css')}}">
@endsection
@section('konten')
    <div class="container my-5">
        <div class="row">
            @foreach ($products as $product)


            @if ($product->stock > 0)
            <div class="col">

                <a href="{{route('product.details', ['category' => $product->category, 'store' => $product->store_name, 'product' => $product->produk_name])}}" class="contents">
                <div class="product">
               
                    <img src="{{asset('storage/images/' . $product->img)}}" alt="img">
                    <h4>{{$product->produk_name}}</h4>
                    <h6>Rp. {{$product->price}} | Stock: {{$product->stock}}</h6>
                    <p>Description: {{$product->description}}</p>
               
                </div>
            </a>
            </div>
            @endif    
            
            
            @endforeach
        </div>
    </div>
@endsection