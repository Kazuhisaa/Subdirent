@extends('layout.applys')

@section('content')

<script>
document.addEventListener("DOMContentLoaded", function() {
    const unit = localStorage.getItem('selectedUnit');
    const price = localStorage.getItem('selectedPrice');
    const title = localStorage.getItem('selectedTitle');

    if (unit && price) {
        document.getElementById('selectedUnit').value = unit;
        document.getElementById('selectedPrice').value = price;
    }
});
</script>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Apply for a Unit</h4>
                </div>
                <div class="card-body">
                    <form id="applyForm">
                        <div class="mb-3">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" id="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="contact">Contact Number</label>
                            <input type="text" id="contact" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="selectedUnit">Selected Unit</label>
                            <input type="text" id="selectedUnit" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="selectedPrice">Price</label>
                            <input type="text" id="selectedPrice" class="form-control" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                    
                    <div id="successMsg" class="alert alert-success mt-3 d-none">
                        ✅ Thank you! Your application has been submitted. We’ll contact you soon.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
