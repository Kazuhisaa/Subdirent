<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Subdirent') }}</title>

    {{-- Local Bootstrap CSS & your custom overrides --}}
    @vite([
        'resources/bootstrap/css/bootstrap.min.css',
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>
<body class="bg-light">

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg" style="background-color: var(--pdogreen)">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="{{ url('/') }}">Subdirent</a>
        </div>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>

    <footer class="text-center py-3" style="background-color: var(--pdogreen); color: #fff;">
        © {{ date('Y') }} Subdirent. All rights reserved.
    </footer>

    {{-- Local Bootstrap JS --}}
    @vite(['resources/bootstrapjs/js/bootstrap.bundle.min.js'])
</body>
</html>
