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
                    @if ($order->status == 'confirm')
                        <form action="{{route('buyer.confirm', $order->id)}}" method="post">
                            @csrf 
                            <button class="btn btn-success my-3">Confirm</button>
                        </form>
                    @endif
                </div>
                <div class="submit">
                    <a href="{{route('buyer.chat', ['buyer' => $order->buyer_email, 'seller' => $order->seller_email, 'trx' => $order->trx_id])}}"><button class="btn btn-primary">Chat</button></a>
                </div>
                
            </div>
    <hr>
    @endforeach
    </div>
    
@endsection