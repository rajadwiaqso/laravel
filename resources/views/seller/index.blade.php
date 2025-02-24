@extends('layout')

@section('style')
    <link rel="stylesheet" href="{{asset('css/seller.css')}}">
@endsection

@section('konten')
    <h1>Hello Seller</h1>
@foreach ($sellers as $seller)

    <div class="container my-5">
        <div class="container row">
            <div class="orders col"><a href="{{route('seller.orders')}}">Orders | {{$orders}}</a><h6></h6></div>
            <div class="orders col"><a href="{{route('seller.confirmed')}}">Confirmed | {{$confirmed}}</a><h6></h6></div>
            <div class="orders col"><a href="{{route('seller.done')}}">Done | {{$done}}</a><h6></h6></div>
        </div>
        <div class="container row py-5">
            <div class="cash col">
                <h5>Credits: {{$seller->credits}}</h5> 
            </div>
            <div class="sold col">
                <h5>Sold: {{$seller->sold_total}}</h5>
            </div>
            <div class="active col">
                <h5>Active: {{$seller->product_total}}</h5>
            </div>
        </div>
        <div class="container row py-5">
            <div class="make col">
                <a href="{{route('product.create')}}">Make Product</a>
            </div>
            <div class="list col">
                <a href="{{route('product.list')}}">Product List</a>
            </div>
        </div>
    </div>
    @endforeach
@endsection