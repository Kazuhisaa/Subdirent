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

     <!-- Available Units Section -->
    <section id="units" class="py-5 bg-light">
        <div class="container">
            <h2 class="fw-bold text-center mb-4">Available Units</h2>
            <div class="row g-4">

<div class="row">
    <!-- Unit A101 -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100">
            <img src="{{ asset('images/unit01.jpg') }}" 
                class="card-img-top unit-img"
                alt="Unit A101"
                data-bs-toggle="modal" 
                data-bs-target="#unitModal"
                data-title="Unit A101"
                data-img="{{ asset('images/unit01.jpg') }}"
                data-description="A spacious 2-bedroom apartment with 1 bathroom, ideal for small families seeking comfort and style."
                data-price="₱15,000/month"
                data-unit="A101"
                style="cursor:pointer;">
            <div class="card-body">
                <h5 class="card-title">Unit A101</h5>
                <p class="card-text">2 Bedrooms • 1 Bath • ₱15,000/month</p>
                
           
                <p class="text-muted">{{ "2-Bedroom Apartment - ₱15,000/month" }}</p>
                
                <a href="/apply" 
                   class="btn btn-primary w-100 book-now-btn" 
                   data-unit="A101"
                   onclick="localStorage.setItem('selectedUnit', this.dataset.unit)">
                   Book Now
                </a>
            </div>
        </div>
    </div>

    <!-- Unit B202 -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100">
            <img src="{{ asset('images/unit01.jpg') }}" 
                class="card-img-top unit-img"
                alt="Unit B202"
                data-bs-toggle="modal" 
                data-bs-target="#unitModal"
                data-title="Unit B202"
                data-img="{{ asset('images/unit01.jpg') }}"
                data-description="A cozy studio unit, perfect for students or working professionals who want convenience at an affordable rate."
                data-price="₱8,500/month"
                data-unit="B202"
                style="cursor:pointer;">
            <div class="card-body">
                <h5 class="card-title">Unit B202</h5>
                <p class="card-text">Studio • ₱8,500/month</p>
                
                <p class="text-muted">{{ "Studio • ₱8,500/month" }}</p>
                
                <a href="/apply" 
                   class="btn btn-primary w-100 book-now-btn" 
                   data-unit="B202"
                   onclick="localStorage.setItem('selectedUnit', this.dataset.unit)">
                   Book Now
                </a>
            </div>
        </div>
    </div>

    <!-- Unit C303 -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100">
            <img src="{{ asset('images/unit01.jpg') }}" 
                class="card-img-top unit-img"
                alt="Unit C303"
                data-bs-toggle="modal" 
                data-bs-target="#unitModal"
                data-title="Unit C303"
                data-img="{{ asset('images/unit01.jpg') }}"
                data-description="A modern 1-bedroom apartment with 1 bathroom, designed for young couples or professionals."
                data-price="₱12,000/month"
                data-unit="C303"
                style="cursor:pointer;">
            <div class="card-body">
                <h5 class="card-title">Unit C303</h5>
                <p class="card-text">1 Bedroom • 1 Bath • ₱12,000/month</p>
                
                <p class="text-muted">{{ "1 Bedroom • 1 Bath • ₱12,000/month" }}</p>
                
                <a href="/apply" 
                   class="btn btn-primary w-100 book-now-btn" 
                   data-unit="C303"
                   onclick="localStorage.setItem('selectedUnit', this.dataset.unit)">
                   Book Now
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Reusable Modal -->
<div class="modal fade" id="unitModal" tabindex="-1" aria-labelledby="unitModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="unitModalLabel">Unit Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img id="unitModalImg" src="" class="img-fluid w-100 mb-3 rounded shadow-sm" alt="Unit">
        <h5 id="unitModalTitle"></h5>
        <p id="unitModalDesc" class="text-muted"></p>
        <h6 id="unitModalPrice" class="fw-bold text-success"></h6>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        <a href="/apply" 
           id="bookNowBtn" 
           class="btn btn-primary"
           onclick="localStorage.setItem('selectedUnit', this.dataset.unit)">
           Book Now
        </a>
      </div>
    </div>
  </div>
</div>


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