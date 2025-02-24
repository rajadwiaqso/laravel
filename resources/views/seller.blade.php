@extends('layout')
@section('konten')
    <form action="{{route('seller.post')}}" method="post">
        @csrf
        <div class="container my-5">
            <h3>Market Name: </h3>
            <input type="text" name="name" id="name">
            <h3>Market Email: </h3>
            <input type="email" name="email" id="email">
            <h3>Upload IDENT</h3>
            <input type="file" name="img" id="img">
            <h3>Message: </h3>
            <textarea name="message" id="text" cols="30" rows="10"></textarea><br>
            <button class="btn btn-primary">Submit</button>
        </div>
    </form>
@endsection