<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
    <h1>Sign Up</h1>
    <p>Already Have Account? <a href="{{route('signin.view')}}">Sign In Here</a></p>

    <form action="{{route('signup.post')}}" method="post">
        @csrf
        <label for="name">Name: </label>
        <input type="text" name="name" id="name"><br>
        <label for="email">Email: </label>
        <input type="email" name="email" id="email"><br>
        <label for="password">Password: </label>
        <input type="password" name="password" id="password"><br>
        <button>Sign Up</button>
        @if (session('failed'))
            <b>{{session('failed')}}</b>
        @endif
    </form>
    
</body>
</html>