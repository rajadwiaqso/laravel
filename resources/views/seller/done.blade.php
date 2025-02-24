@extends('layout')

@section('konten')
    <h1>Done</h1>
    <div class="container py-5">
        
        @foreach ($done as $confirm)
        
            <div class="col">
                <div class="details">
                    <h3>{{$confirm->product}}</h3>
                    <h3>{{$confirm->price}}</h3>
                    <h3>{{$confirm->category}}</h3>
                    <h3>{{$confirm->status}}</h3>
                </div>
                <div class="submit">
                    <a href="{{route('seller.chat', ['buyer' => $confirm->buyer_email, 'seller' => $confirm->seller_email, 'trx' => $confirm->trx_id])}}"><button class="btn btn-primary">Chat</button></a>
                </div>
                
            </div>
    <hr>
    @endforeach
    </div>
@endsection