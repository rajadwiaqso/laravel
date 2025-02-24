    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.css')}}">
        <link rel="stylesheet" href="{{asset('css/style.css')}}">
        @yield('style')
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light sticky-top">
            <div class="container">
                <!-- Brand/Logo -->
                <a class="navbar-brand fw-bold" href="{{route('index')}}">
                    <i class="bi bi-shop"></i> MarketPlace
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
                            <a class="nav-link" href="#">Orders</a>
                        </li>
                    </ul>
    
                    <!-- Search Bar -->
                    <form class="d-flex mx-lg-4 flex-grow-1">
                        <div class="input-group shadow-sm">
                            <input type="search" class="form-control search-input" placeholder="Search products...">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search">Search  </i>
                            </button>
                        </div>
                    </form>
    
                    <!-- Right Icons -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-heart"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-cart"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="#">Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul>   
                        </li>
                    </ul>
                    <a class="mx-2" href="{{route('profile')}}">Profile</a>
                    <form action="{{route('logout')}}" method="post">
                        @csrf 
                        <button class="btn btn-danger mx-2">Logout</button>
                    </form>
                    @if (Auth::user()->role == 'buyer')
                        <a href="{{route('seller.form')}}" class="btn btn-primary">Be Seller</a>
                    @endif
                </div>
            </div>
        </nav>

        @yield('konten')
    
        
        <script src="{{asset('bootstrap/js/bootstrap.js')}}"></script>
    </body>
    </html>
        
    
    