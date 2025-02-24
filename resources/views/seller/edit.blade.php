@extends('layout')

@section('konten')
    <h1>Edit</h1>
    <div class="container my-5">
    <form action="{{route('product.edit.submit', $product->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        <h5>Product Name:</h5>
        <input type="text" name="name" id="" value="{{$product->produk_name}}">
        <h5>Price:</h5>
        <input type="number" name="price" id="" value="{{$product->price}}">
        <h5>Stock:</h5>
        <input type="number" name="stock" id="" value="{{$product->stock}}">
        <h5>Img:</h5>
        <img src="{{$product->img}}" alt="{{$product->img}}"><br>
        <input type="file" name="img" id="">
        <h5>Description:</h5>
        <textarea name="description" id="" cols="30" rows="10">{{$product->description}}</textarea><br>  
        <button class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection