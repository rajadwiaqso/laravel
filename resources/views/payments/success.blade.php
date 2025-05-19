@extends('layout')

@section('konten')
 
    <div class="container mt-5">
        <h2 class="text-center">Payment Success</h2>
        <p class="text-center">Thank you for your purchase!</p>
        <p class="text-center">Your payment has been successfully processed.</p>
        <a href="{{ route('index') }}" class="btn btn-primary">Go to Home</a>
        <a href="{{ route('buyer.orders') }}" class="btn btn-secondary">View Orders</a>
        <a href="{{ route('buyer.chat', ['trx' => $data->trx_id]) }}" class="btn btn-secondary">Chat Penjual</a>
    
        <div class="text-center mt-4">
            <h4>Order Details</h4>
            <p><strong>Product Name:</strong> {{ $data->product }}</p>
            <p><strong>Price:</strong> Rp. {{ number_format($data->price) }}</p>
            <p><strong>Quantity:</strong> {{ $data->quantity }}</p>
            <p><strong>Total Amount:</strong> Rp. {{ number_format($data->total) }}</p>
            {{-- <p><strong>Payment Method:</strong> {{ $payment_method }}</p>
            <p><strong>Transaction ID:</strong> {{ $transaction_id }}</p>
            <p><strong>Order Status:</strong> {{ $order_status }}</p> --}}
        </div>

    </div>
@endsection