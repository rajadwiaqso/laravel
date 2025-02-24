@extends('layout')
@section('konten')


    <div class="container mt-5">
        <form action="{{route('profile.post', Auth::user())}}" method="post">
            @csrf
            
    <h1>Name: </h1>
    <input type="text" name="name" id="name" value="{{Auth::user()->name}}">
    <button class="btn btn-primary">Change</button>
</form>
</div>
@endsection