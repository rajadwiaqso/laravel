@extends('layout')

@section('konten')
    <h1>Details</h1>
  <div class="container my-3">
    <img src="{{asset('storage/images/' . $product->img)}}" alt="{{$product->img}}">
    <h2>{{$product->produk_name}}</h2>
    <h4>Price: {{$product->price}} | Stock: {{$product->stock}}</h4>
    <p>Description: {{$product->description}}</p>
   @if ($product->stock > 0)
   <form action="{{route('product.buy', ['category' => $product->category, 'store' => $product->store_name, 'product' => $product->produk_name])}}" method="post">
    @csrf
    <button class="btn btn-primary">Buy</button>
  </form>
   @else
   <button class="btn btn-danger disabled ">Out Of Order</button>
   @endif
    
  </div>
@endsection