@extends('layout')

@section('konten')
    <h1>Orders</h1>
    <div class="container py-5">
        
        @foreach ($orders as $order)
        
            <div class="col">
                <div class="details">
                    <h3>{{$order->product}}</h3>
                    <h3>{{$order->price}}</h3>
                    <h3>{{$order->category}}</h3>
                    <h3>{{$order->status}}</h3>
                </div>
                <div class="submit">
                    <a href="{{route('seller.chat', ['buyer' => $order->buyer_email, 'seller' => $order->seller_email, 'trx' => $order->trx_id])}}"><button class="btn btn-primary">Chat</button></a>
                    <form action="{{route('seller.orders.accept', $order->id)}}" method="post">
                        @csrf
                    <button class="btn btn-success">Confirm</button>
                </form>
                <form action="{{route('seller.orders.reject', $order->id)}}" method="POST">
                    @csrf
                    <button class="btn btn-danger">Reject</button>
                </form>
                </div>
                
            </div>
    <hr>
    @endforeach
    </div>
    
@endsection