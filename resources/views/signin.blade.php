{{-- filepath: /home/rajadwiaqso/Desktop/market 4.0/resources/views/signin.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign In</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}"> {{-- File CSS Khusus untuk Autentikasi --}}
    <style>
        body {
            background: linear-gradient(to right, #5a4fcf, #4838e8);
            color: #333;
            font-family: 'Arial', sans-serif;
        }

        .card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #5a4fcf;
            border: none;
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 1rem;
        }

        .btn-primary:hover {
            background-color: #3f2dbf;
        }

        .form-control:focus {
            border-color: #5a4fcf;
            box-shadow: 0 0 8px rgba(90, 79, 207, 0.5);
        }

        .form-check-label {
            font-size: 0.9rem;
        }

        .auth-footer a {
            color: #5a4fcf;
            text-decoration: none;
            font-weight: bold;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .input-group-text {
            background-color: #f1f1f1;
            border: none;
        }

        .input-group-text i {
            color: #5a4fcf;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow-sm p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-circle fs-1 text-primary"></i>
                        <h1 class="mt-3" style="font-family: 'Playfair Display', serif; font-weight: bold;">Sign In</h1>
                        <p class="text-muted">Belum punya akun? <a href="{{ route('signup.view') }}">Daftar di sini</a></p>
                    </div>

                    @if (session('failed'))
                        <div class="alert alert-danger mb-3">{{ session('failed') }}</div>
                    @endif

                    <form action="{{ route('signin.post') }}" method="post">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label">Email:</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Password:</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Sign In</button>
                        <div class="mt-4 text-center auth-footer">
                            <a href="{{route('password.reset.view')}}">Lupa password?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>