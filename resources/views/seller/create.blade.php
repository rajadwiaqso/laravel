@extends('layout')
@section('konten')
    <h1>Make Product</h1>
    <div class="container my-5">
        <form action="{{route('product.create.submit')}}" method="post" enctype="multipart/form-data">
            @csrf
            <h5>Product Name:</h5>
            <input type="text" name="product_name" id="">
            <h5>Category:</h5>
            <select name="category" id="">
                <option value="pubg">Pubg</option>
                <option value="mobile-legends">Mobile Legends</option>
                <option value="free-fire">Free Fire</option>
                <option value="roblox">Roblox</option>
            </select>
            <h5>Price:</h5>
            <input type="number" name="price" id="">
            <h5>Stock:</h5>
            <input type="number" name="stock" id="">
            <h5>Img:</h5>
            <input type="file" name="image" id="">
            <h5>Description:</h5>
            <textarea name="description" id="" cols="30" rows="10"></textarea><br>  
            <button class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection