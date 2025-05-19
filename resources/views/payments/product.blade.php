@extends('layout')

@section('konten')
  
    <div class="container">
        <h1>Payment</h1>
        <div class="row">
            <div class="col-md-6">
                <h2>Payment Details</h2>
                <p>Product Name: {{ $data->produk_name }}</p>
                <p>Price: {{ $data->price }} x {{ $kuantitas}}</p>
                {{-- <p>Quantity: {{ $quantity }}</p> --}}
                <p>Total Amount: {{ $total }}</p>
            </div>
            <div class="col-md-6">
                <h2>Payment Method</h2>
                <form action="{{ route('product.buy', ['category' => $data->category, 'store' => $data->store_name, 'product' => $data->produk_name, 'id_product' => $data->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $data->id }}">
                    <input type="hidden" name="quantity" value="{{ $kuantitas }}">
                    <input type="hidden" name="total_amount" value="{{ $total }}">
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Select Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="" disabled selected>Select a payment method</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Pay Now</button>
                </form>
            </div>
        </div>
   
@endsection