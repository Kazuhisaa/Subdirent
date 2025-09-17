<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Subdirent') }}</title>

    {{-- Bootstrap CSS + custom overrides --}}
    @vite([
    'resources/bootstrap/css/bootstrap.min.css',
    'resources/css/app.css',
    'resources/js/app.js'
    ])
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--pdogreen);">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">Subdirent</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto text-center text-lg-start">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/features') }}">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/units') }}">Units</a></li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light ms-lg-2 mt-2 mt-lg-0" href="{{ route('login') }}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-auto text-center py-3" style="background-color: var(--pdogreen); color: #fff;">
        <div class="container">
            <p class="mb-1">© {{ date('Y') }} Subdirent. All rights reserved.</p>
            <p class="small mb-0">
                <a href="#" class="text-white text-decoration-none me-3">Privacy Policy</a>
                <a href="#" class="text-white text-decoration-none">Terms of Service</a>
            </p>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    @vite(['resources/bootstrapjs/js/bootstrap.bundle.min.js'])
</body>

</html>