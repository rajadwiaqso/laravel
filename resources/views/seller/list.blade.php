@extends('layout')
@section('style')
    <link rel="stylesheet" href="{{asset('css/product.css')}}">
@endsection
@section('konten')
    <h1>List</h1>

    <div class="container my-5">
        <div class="row">
            @foreach ($list as $product)
                
            
            <div class="col">
                <div class="product">
                    <img alt="img" src="{{asset('storage/images/' . $product->img)}}">
                    <h4>{{$product->produk_name}}</h4>
                    <h6>Rp. {{$product->price}} | Stock: {{$product->stock}}</h6>
                    <p>Description: {{$product->description}}</p>
                    <a href="{{route('product.edit', $product->id)}}">
                        <button class="btn btn-primary">Edit</button>
                    </a>
                    <form action="{{route('product.delete', $product->id)}}" method="post">
                        @csrf
                        <button class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection