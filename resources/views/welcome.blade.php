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
            <ul class="navbar-nav ms-auto text-center text-lg-start">
                <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="#units">Units</a></li>
                <li class="nav-item"><a class="btn btn-outline-light ms-lg-2 mt-2 mt-lg-0" href="#">Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Banner -->
<header class="bg-light py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">Welcome to Subdirent</h1>
        <p class="lead text-muted">Manage tenants, payments, and units all in one place.</p>
        <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
            <a href="#units" class="btn btn-primary btn-lg">Get Started</a>
            <a href="#" class="btn btn-outline-secondary btn-lg">Learn More</a>
        </div>
    </div>
</header>

<!-- Available Units -->
<section id="units" class="py-5 bg-light">
    <div class="container">
        <h2 class="fw-bold text-center mb-4">Available Units</h2>
        <div class="row g-4">
            @foreach([
                ['img'=>'unit01.jpg','unit'=>'A101','title'=>'2-Bedroom Apartment','price'=>'₱15,000/month','desc'=>'A spacious 2-bedroom apartment with 1 bathroom, ideal for small families.'],
                ['img'=>'unit02.png','unit'=>'B202','title'=>'Studio Apartment','price'=>'₱8,500/month','desc'=>'A cozy studio unit, perfect for students or working professionals.'],
                ['img'=>'unit03.jpg','unit'=>'C303','title'=>'1-Bedroom Apartment','price'=>'₱12,000/month','desc'=>'A modern 1-bedroom apartment with 1 bathroom, designed for young couples or professionals.'],
            ] as $unit)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">
                        <img src="{{ asset('images/'.$unit['img']) }}" 
                             class="card-img-top unit-img" 
                             alt="Unit {{ $unit['unit'] }}"
                             data-unit="{{ $unit['unit'] }}"
                             data-title="{{ $unit['title'] }}"
                             data-price="{{ $unit['price'] }}"
                             data-img="{{ asset('images/'.$unit['img']) }}"
                             data-description="{{ $unit['desc'] }}"
                             data-bs-toggle="modal" 
                             data-bs-target="#unitModal"
                             onclick="showUnitDetails(this)">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Unit {{ $unit['unit'] }}</h5>
                            <p class="card-text">{{ $unit['title'] }} • {{ $unit['price'] }}</p>
                            <div class="mt-auto d-flex flex-column flex-sm-row justify-content-between gap-2">
                                <button class="btn btn-primary w-100 w-sm-auto" onclick="showBookingForm(this)" 
                                    data-unit="{{ $unit['unit'] }}"
                                    data-title="{{ $unit['title'] }}"
                                    data-price="{{ $unit['price'] }}"
                                    data-img="{{ asset('images/'.$unit['img']) }}"
                                    data-description="{{ $unit['desc'] }}"
                                    data-bs-toggle="modal" data-bs-target="#unitModal">
                                    Book Now
                                </button>
                                <button class="btn btn-success w-100 w-sm-auto" onclick="showApplyForm(this)" 
                                    data-unit="{{ $unit['unit'] }}"
                                    data-title="{{ $unit['title'] }}"
                                    data-price="{{ $unit['price'] }}"
                                    data-img="{{ asset('images/'.$unit['img']) }}"
                                    data-description="{{ $unit['desc'] }}"
                                    data-bs-toggle="modal" data-bs-target="#unitModal">
                                    Apply to Rent
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Hidden Partials -->
<div id="bookingFormPartial" class="d-none">@include('partials._bookingForm')</div>
<div id="applyFormPartial" class="d-none">@include('partials._applyForm')</div>

<!-- Unit Modal -->
<div class="modal fade" id="unitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Unit Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3" id="unitModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function showUnitDetails(el){
    const modalBody = document.getElementById('unitModalBody');
    modalBody.innerHTML = `
        <div class="row g-3">
            <div class="col-12 col-md-5">
                <img src="${el.dataset.img}" class="img-fluid rounded" alt="Unit ${el.dataset.unit}">
            </div>
            <div class="col-12 col-md-7">
                <h3>Unit ${el.dataset.unit}: ${el.dataset.title}</h3>
                <h5 class="text-success">${el.dataset.price}</h5>
                <p>${el.dataset.description}</p>
            </div>
        </div>
    `;
}

function showBookingForm(btn){
    const modalBody = document.getElementById('unitModalBody');
    modalBody.innerHTML = document.getElementById('bookingFormPartial').innerHTML;

    modalBody.querySelector('#unitTitle').textContent = btn.dataset.title;
    modalBody.querySelector('#unitName').textContent = btn.dataset.unit;
    modalBody.querySelector('#unitPrice').textContent = btn.dataset.price;
    modalBody.querySelector('#unitDescription').textContent = btn.dataset.description;
    modalBody.querySelector('#unitImg')?.setAttribute('src', btn.dataset.img);
}

function showApplyForm(btn){
    const modalBody = document.getElementById('unitModalBody');
    modalBody.innerHTML = document.getElementById('applyFormPartial').innerHTML;

    modalBody.querySelector('#selectedTitle')?.setAttribute('value', btn.dataset.title);
    modalBody.querySelector('#selectedUnit')?.setAttribute('value', btn.dataset.unit);
    modalBody.querySelector('#selectedPrice')?.setAttribute('value', btn.dataset.price);
}
</script>

<!-- Footer -->
<footer class="mt-auto" style="background-color: #0f3c28; padding:1.5rem 0; text-align:center;">
    <div class="container">
        <p style="color:#fff; font-size:0.95rem; margin:0;">
            © {{ date('Y') }} Subdirent. All rights reserved.
        </p>
        <p style="margin:0; font-size:0.95rem;">
            <a href="#" style="color:#d4a017; text-decoration:none; font-weight:500; margin-right:0.5rem;">Privacy Policy</a>|
            <a href="#" style="color:#d4a017; text-decoration:none; font-weight:500; margin-left:0.5rem;">Terms of Service</a>
        </p>
    </div>
</footer>

<style>
footer a:hover {
    color: #fff !important;
}
</style>


@vite('resources/bootstrapjs/js/bootstrap.bundle.min.js')
</body>
</html>
