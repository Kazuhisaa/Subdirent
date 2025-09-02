<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Management System</title>

   @vite([
    'resources/bootstrap/css/bootstrap.min.css',
    'resources/css/app.css',
    'resources/js/app.js', 
    'resources/bootstrapjs/js/bootstrap.bundle.min.js'
    ])

</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Subdirent</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Units</a></li>
                    <li class="nav-item"><a class="btn btn-outline-light ms-2" href="#">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Banner Section -->
    <header class="bg-light py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">Welcome to Subdirent wher u can manage efficiently </h1>
            <p class="lead text-muted">Subdirent tracks tenants, payments, and units all in one place.</p>
            <a href="#" class="btn btn-primary btn-lg me-2">Get Started</a>
            <a href="#" class="btn btn-outline-secondary btn-lg">Learn More</a>
        </div>
    </header>

    <!-- Features -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="card p-4 shadow-sm border-0">
                        <h5 class="fw-bold">📊 Dashboard</h5>
                        <p class="text-muted">Monitor all rental activities with an easy-to-use dashboard.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 shadow-sm border-0">
                        <h5 class="fw-bold">🏠 Units</h5>
                        <p class="text-muted">Track available and occupied units with detailed records.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 shadow-sm border-0">
                        <h5 class="fw-bold">💰 Payments</h5>
                        <p class="text-muted">Easily manage rent collection and payment history.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-#0f3c28 text-light text-center py-3">
        <p class="mb-0">&copy; {{ date('Y') }} Subdirent. All rights reserved.</p>
    </footer>

    @vite('resources/bootstrapjs/js/bootstrap.bundle.min.js')
</body>
</html>

 <!-- 
php artisan serve = START server
npm run build = Save code for server
npm run dev = see if server working
cd "C:\Users\jorej\OneDrive\Documents\GitHub\Subdirent" = Change the dir to fit your liking
-->