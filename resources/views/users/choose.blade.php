<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    {{session('user_type')}}
    
    <form action="{{route('choose.buyer')}}" method="post">
        @csrf
        <button type="submit">Buyer</button>
    </form>

    @if (Auth::user()->is_seller == 1)
    <form action="{{route('choose.seller')}}" method="post">
        @csrf
        <button type="submit">Seller</button>
    </form>
    @else
    <button type="submit" disabled>Seller</button>
    @endif

</body>
</html>