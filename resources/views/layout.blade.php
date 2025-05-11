{{-- filepath: /home/rajadwiaqso/Desktop/market 4.0/resources/views/layout.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{env('APP_NAME')}}</title>

    @vite(['resources/js/app.js'])

    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <script src="{{asset('bootstrap/js/bootstrap.js')}}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('style')

    <style>
        /* Navbar Styling */
        .navbar {
            background: linear-gradient(90deg, #5a4fcf, #4838e8);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar .nav-link {
            color: white;
            font-weight: 500;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .navbar .nav-link:hover {
            color: #d1cfff;
            transform: scale(1.1);
        }

        .navbar .btn-primary {
            background-color: #574bff;
            border: none;
            border-radius: 20px;
            padding: 5px 15px;
        }

        .navbar .btn-primary:hover {
            background-color: #3f2dbf;
        }

        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Footer Styling */
        footer {
            background: linear-gradient(90deg, #4838e8, #5a4fcf);
            color: white;
            padding: 30px 0;
            font-size: 0.9rem;
        }

        footer a {
            color: #d1cfff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: white;
        }

        footer .social-icons i {
            font-size: 1.5rem;
            margin: 0 10px;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        footer .social-icons i:hover {
            color: #d1cfff;
            transform: scale(1.2);
        }
        .dropdown-item {
            padding: 10px 20px;
        }
        .dropdown-item:hover {
            background-color: #f8f9faef;
            color: #000;
        }
        .dropdown-item:active {
            background-color: #e2e6ea;
            color: #000;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <!-- Brand/Logo -->
            <a class="navbar-brand fw-bold" href="{{route('index')}}">
                <i class="bi bi-shop"></i> {{env('APP_NAME')}}
            </a>

            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Nav Items -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Navigation Links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Categories
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Electronics</a></li>
                            <li><a class="dropdown-item" href="#">Fashion</a></li>
                            <li><a class="dropdown-item" href="#">Home & Living</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                <a class="nav-link {{ request()->is('orders') ? 'active' : '' }}" 
                   href="{{ Auth::check() && (Auth::user()->role == 'seller') ? route('seller.orders.all') : route('buyer.orders') }}">
                    {{-- Check if user is logged in --}}
                    Orders
                </a>
                    </li>
                </ul>

                <!-- Search Bar -->
                <form class="d-flex mx-lg-4 flex-grow-1">
                    <div class="input-group shadow-sm">
                        <input type="search" class="form-control search-input" placeholder="Search products or category..." id="navInput">
                        <button class="btn btn-primary" type="button" id="navButton">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Right Icons -->
                <ul class="navbar-nav">
                    @if (Auth::check() && (Auth::user()->role == 'buyer')) 
                   
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-heart"></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{route('buyer.orders')}}">
                            <i class="bi bi-cart"></i>
                        </a>
                    </li>
                    @endif
                    
                
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i>
                </a>
                @if (Auth::check())
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{route('profile')}}">Profile</a></li>
                    <form action="{{route('logout')}}" method="post">
                        @csrf
                    <li><button class="dropdown-item">Logout</button></li>
                </form>
                </ul>
                @else
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{route('signin.view')}}">Login</a></li>
                    <li><a class="dropdown-item" href="{{route('signup.view')}}">Register</a></li>
                </ul>
                @endif
            </li>
            @if (Auth::check() && (Auth::user()->role == 'buyer'))
            <li class="nav-item">
                <a class="btn btn-warning mx-2" href="{{route('seller.form')}}">Daftar sebagai Seller</a>
            </li>
            @endif
                </ul>

               
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('konten')

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p>&copy; 2025 {{env('APP_NAME')}}. All Rights Reserved.</p>
            <div class="social-icons">
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-twitter"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
            </div>
        </div>
    </footer>

    <script>

        const searchButtons = document.getElementById('navButton');
const searchInputs = document.getElementById('navInput')

@if (Auth::check() && (Auth::user()->role == 'buyer'))
searchButtons.addEventListener('click', function() {
    const query = searchInputs.value;
    window.location.href = `/products/search?query=${query}`;    
});
 
 @else

searchButtons.addEventListener('click', function() {
    const query = searchInputs.value;
    window.location.href = `/seller/products/search?query=${query}`;    
});
 
@endif


    </script>
    @yield('script')
</body>
</html>