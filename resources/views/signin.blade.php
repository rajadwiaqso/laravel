<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
    <h1>Sign In</h1>
    <p>Don't have Account yet? <a href="{{route('signup.view')}}">Sign Up Here</a></p>

    <form action="{{route('signin.post')}}" method="post">
        @csrf 
        <label for="email">Email: </label>
        <input type="email" name="email" id="email"><br>
        <label for="password">Password: </label>
        <input type="password" name="password" id="password"><br>
        <button>Sign In</button>
        @if (session('failed'))
            <b>{{session('failed')}}</b>
        @endif
    </form>
    
</body>
</html>