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

    <!-- Banner with Carousel Background -->
    <header>
        <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- First Slide -->
                <div class="carousel-item active">
                    <img src="/images/back1.png" class="d-block w-100" alt="Unit 1"
                        style="height: 500px; object-fit: cover;">
                    <div class="carousel-caption d-flex flex-column justify-content-center align-items-center h-100">
                        <div class="caption-bg p-4 rounded">
                            <h1 class="display-4 fw-bold text-white">Welcome to Subdirent</h1>
                            <p class="lead text-white-50">Manage tenants, payments, and units all in one place.</p>
                            <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                                <a href="#units" class="btn btn-primary btn-lg">Get Started</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Second Slide -->
                <div class="carousel-item">
                    <img src="/images/back2.png" class="d-block w-100" alt="Unit 2"
                        style="height: 500px; object-fit: cover;">
                    <div class="carousel-caption d-flex flex-column justify-content-center align-items-center h-100">
                        <div class="caption-bg p-4 rounded">
                            <h1 class="display-4 fw-bold text-white">Easy Tenant Management</h1>
                            <p class="lead text-white-50">Track applications and keep records effortlessly.</p>
                            <a href="#" class="btn btn-primary btn-lg">Book now</a>
                        </div>
                    </div>
                </div>

                <!-- Third Slide -->
                <div class="carousel-item">
                    <img src="/images/back3.png" class="d-block w-100" alt="Unit 3"
                        style="height: 500px; object-fit: cover;">
                    <div class="carousel-caption d-flex flex-column justify-content-center align-items-center h-100">
                        <div class="caption-bg p-4 rounded">
                            <h1 class="display-4 fw-bold text-white">Stay Organized</h1>
                            <p class="lead text-white-50">Payments, units, tenants — all in one dashboard.</p>
                            <a href="#footer" class="btn btn-primary btn-lg">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </header>

    <!-- Available Units -->
    <section id="units" class="py-5" style="background: #f9f9f9;">
        <div class="container">
            <h2 class="fw-bold text-center mb-5 text-success">🏡 Available Units</h2>
            <div class="row g-4">
                @forelse($units as $unit)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 unit-card border-0 shadow">
                        <!-- Image -->
                        <div class="position-relative">
                            <img src="{{ $unit->image ? asset('uploads/units/'.$unit->image) : asset('images/no-image.png') }}"
                                class="card-img-top unit-img"
                                alt="{{ $unit->title }}"
                                data-unit="{{ $unit->id }}"
                                data-title="{{ $unit->title }}"
                                data-price="₱{{ number_format($unit->price, 2) }}/month"
                                data-img="{{ $unit->image ? asset('uploads/units/'.$unit->image) : asset('images/no-image.png') }}"
                                data-description="{{ $unit->description }}"
                                data-bs-toggle="modal"
                                data-bs-target="#unitModal"
                                onclick="showUnitDetails(this)">
                            <span class="badge bg-success position-absolute top-0 end-0 m-3 shadow">For Rent</span>
                        </div>

                        <!-- Content -->
                        <div class="card-body d-flex flex-column bg-white">
                            <h5 class="fw-bold text-success">{{ $unit->title }}</h5>
                            <p class="text-muted mb-2"><i class="bi bi-geo-alt-fill text-warning"></i> {{ $unit->location }}</p>
                            <h6 class="fw-bold mb-3 text-gold">₱{{ number_format($unit->price, 2) }}/month</h6>
                            <p class="text-dark text-truncate-3">{{ $unit->description }}</p>

                            <div class="mt-auto d-flex gap-2">
                                <button class="btn btn-outline-success flex-fill"
                                    onclick="showBookingForm(this)"
                                    data-unit="{{ $unit->id }}"
                                    data-title="{{ $unit->title }}"
                                    data-price="₱{{ number_format($unit->price, 2) }}/month"
                                    data-img="{{ $unit->image ? asset('uploads/units/'.$unit->image) : asset('images/no-image.png') }}"
                                    data-description="{{ $unit->description }}"
                                    data-bs-toggle="modal" data-bs-target="#unitModal">
                                    <i class="bi bi-calendar-check"></i> Book Now
                                </button>
                                <button class="btn btn-gold flex-fill"
                                    onclick="showApplyForm(this)"
                                    data-unit="{{ $unit->id }}"
                                    data-title="{{ $unit->title }}"
                                    data-price="₱{{ number_format($unit->price, 2) }}/month"
                                    data-img="{{ $unit->image ? asset('uploads/units/'.$unit->image) : asset('images/no-image.png') }}"
                                    data-description="{{ $unit->description }}"
                                    data-bs-toggle="modal" data-bs-target="#unitModal">
                                    <i class="bi bi-pencil-square"></i> Apply
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-muted">No units available right now.</p>
                @endforelse
            </div>
        </div>
    </section>
    <div id="bookingFormPartial" class="d-none">@include('partials._bookingForm')</div>
    <div id="applyFormPartial" class="d-none">@include('partials._applyForm')</div>

    <!-- Custom CSS -->
    <style>
        .unit-card {
            border-radius: 15px;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            overflow: hidden;
            background: #fff;
        }

        .unit-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .unit-img {
            height: 220px;
            object-fit: cover;
        }

        .text-truncate-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .text-gold {
            color: #b8860b;
            /* dark goldenrod */
        }

        .btn-gold {
            background-color: #b8860b;
            color: #fff;
            border: none;
        }

        .btn-gold:hover {
            background-color: #daa520;
            /* goldenrod */
            color: #fff;
        }
    </style>


    <!-- Footer -->
    <footer id="footer">
        <div class="container">
            <div class="row text-start text-md-start">

                <!-- Left Column: Location + Social + About Us -->
                <div class="col-md-6 mb-3">
                    <h5 class="text-pdogold fw-bold mb-3">Our Location</h5>
                    <p class="footer-text">
                        <i class="bi bi-geo-alt-fill text-pdogold me-2"></i>
                        Pueblo de Oro Development Corporation<br>
                        17th Floor Robinsons Summit Center,<br>
                        6783 Ayala Avenue, Makati City 1226, Philippines
                    </p>

                    <h5 class="text-pdogold fw-bold mt-4 mb-2">Follow Us</h5>
                    <p class="footer-text mb-3">
                        <span class="footer-links">
                            <a href="#"><i class="bi bi-facebook me-1"></i>Facebook</a>
                            <a href="#"><i class="bi bi-youtube me-1"></i>YouTube</a>
                            <a href="#"><i class="bi bi-instagram me-1"></i>Instagram</a>
                        </span>
                    </p>

                    <h5 class="text-pdogold fw-bold mt-4 mb-2">About Us</h5>
                    <p class="footer-text">
                        Subdirent is a property management platform designed to make
                        renting easier for tenants and property owners.
                        We simplify tenant management, unit booking, and payments
                        all in one place.
                    </p>
                </div>

                <!-- Right Column: Contacts -->
                <div class="col-md-6 mb-3">
                    <h5 class="text-pdogold fw-bold mb-3">Contact Us</h5>
                    <p class="footer-text">
                        <strong><i class="bi bi-building text-pdogold me-2"></i>Head Office:</strong>
                        +63 (2) 8790-2200
                    </p>
                    <p class="footer-text">
                        <strong><i class="bi bi-geo-fill text-pdogold me-2"></i>Sto. Tomas, Batangas:</strong>
                        +63 (2) 8736-3291 | +63 (43) 781-5841 | +63 (947) 998-0069
                    </p>
                    <p class="footer-text">
                        <strong><i class="bi bi-geo-fill text-pdogold me-2"></i>Malvar, Batangas:</strong>
                        +63 (2) 8400-6428 | +63 (917) 114-5856
                    </p>
                    <p class="footer-text">
                        <strong><i class="bi bi-geo-fill text-pdogold me-2"></i>Pampanga:</strong>
                        +63 (917) 833-6154 | +63 (947) 998-0078 | +63 (932) 855-0176
                    </p>
                    <p class="footer-text">
                        <strong><i class="bi bi-geo-fill text-pdogold me-2"></i>Cagayan De Oro:</strong>
                        +63 (88) 858-8976 | +63 (917) 102-8736
                    </p>
                    <p class="footer-text">
                        <strong><i class="bi bi-geo-fill text-pdogold me-2"></i>Cebu:</strong>
                        +63 (32) 888-6146 | +63 (32) 341-5573 | +63 (917) 889-7966
                    </p>
                </div>
            </div>

            <hr class="footer-divider">

            <div class="text-center">
                <p class="footer-text">© {{ date('Y') }} Subdirent. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Icons (make sure this is included in your layout) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        footer a:hover {
            color: #fff !important;
        }
    </style>
    <script>
        function showBookingForm(btn) {
            const modalBody = document.getElementById('unitModalBody');
            modalBody.innerHTML = document.getElementById('bookingFormPartial').innerHTML;

            modalBody.querySelector('#unitTitle').textContent = btn.dataset.title;
            modalBody.querySelector('#unitName').textContent = btn.dataset.unit;
            modalBody.querySelector('#unitPrice').textContent = btn.dataset.price;
            modalBody.querySelector('#unitDescription').textContent = btn.dataset.description;
            modalBody.querySelector('#unitImg')?.setAttribute('src', btn.dataset.img);
        }

        function showApplyForm(btn) {
            const modalBody = document.getElementById('unitModalBody');
            modalBody.innerHTML = document.getElementById('applyFormPartial').innerHTML;

            modalBody.querySelector('#selectedTitle')?.setAttribute('value', btn.dataset.title);
            modalBody.querySelector('#selectedUnit')?.setAttribute('value', btn.dataset.unit);
            modalBody.querySelector('#selectedPrice')?.setAttribute('value', btn.dataset.price);
        }
    </script>

    @vite('resources/bootstrapjs/js/bootstrap.bundle.min.js')
</body>



</html>