@extends('layout')

@section('style')
    <link rel="stylesheet" href="{{asset('css/index.css')}}">
@endsection

@section('konten')


    <h1>Hello {{Auth::user()->name}}</h1>
    
    <div class="container my-5">
        <div class="row">
            
           
            @foreach ($products as $product)
            <div class="col"><a href="{{route('products.category', $product)}}" class="item">{{$product}}</a></div>
            @endforeach
        
            
        </div>
    </div>
    
    <a href="{{route('buyer.orders')}}"><button class="btn btn-primary">Orders</button></a>
    
    @endsection