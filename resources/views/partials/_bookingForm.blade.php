<div class="container my-4">
    <form id="bookingForm" enctype="multipart/form-data" method="POST" action="{{ route('booking.store') }}">
        @csrf
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white py-3">
                <h3 class="fw-bold mb-0">📅 Book a Unit</h3>
            </div>

            <div class="card-body p-4">
                <!-- Unit Selection -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="selectedUnit" class="form-label fw-semibold">Select Unit</label>
                        <select id="selectedUnit" name="unit" class="form-select" required>
                            <option value="">-- Choose a Unit --</option>
                            <option value="Unit A" data-title="1-Bedroom Condo" data-price="15000" data-location="Makati City">Unit A</option>
                            <option value="Unit B" data-title="2-Bedroom Apartment" data-price="22000" data-location="Quezon City">Unit B</option>
                            <option value="Unit C" data-title="Studio Type" data-price="10000" data-location="Taguig City">Unit C</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="selectedTitle" class="form-label fw-semibold">Unit Title</label>
                        <input type="text" id="selectedTitle" name="title" class="form-control" readonly>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label for="selectedPrice" class="form-label fw-semibold">Price (₱)</label>
                        <input type="text" id="selectedPrice" name="price" class="form-control" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="selectedLocation" class="form-label fw-semibold">Location</label>
                        <input type="text" id="selectedLocation" name="location" class="form-control" readonly>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Date & Time -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="date" class="form-label fw-semibold">Select Date</label>
                        <input type="date" id="date" name="date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Select Time Slot</label>
                        <div class="row g-2" id="timeSlots"></div>
                        <input type="hidden" id="time" name="time_slot" required>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Contact Info -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Full Name</label>
                        <input type="text" id="name" name="full_name" class="form-control" placeholder="Juan Dela Cruz" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="juan@email.com" required>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label for="contact" class="form-label fw-semibold">Phone Number</label>
                        <input type="text" id="contact" name="contact" class="form-control" placeholder="09XXXXXXXXX" required>
                    </div>
                    <div class="col-md-6">
                        <label for="notes" class="form-label fw-semibold">Additional Notes (Optional)</label>
                        <textarea id="notes" name="notes" class="form-control" rows="2" placeholder="Any special requests?"></textarea>
                    </div>
                </div>

                <div class="mt-4 d-grid gap-2">
                    <button type="submit" class="btn btn-primary fw-bold">✅ Confirm Booking</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">⬅ Back</button>
                </div>
            </div>
        </div>
    </form>

    <div id="successMsg" class="alert alert-success mt-4 d-none text-center fw-bold">
        ✅ Your booking has been submitted successfully! We’ll contact you soon.
    </div>
</div>

<script>
    // Handle form submit
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (response.ok) {
                    document.getElementById('successMsg').classList.remove('d-none');
                    form.classList.add('d-none');
                } else {
                    alert('❌ Submission failed. Please check your input.');
                }
            })
            .catch(() => alert('❌ Submission failed. Please try again.'));
    });

    // Auto-fill fields when selecting a unit
    document.getElementById('selectedUnit').addEventListener('change', function() {
        let option = this.options[this.selectedIndex];
        document.getElementById('selectedTitle').value = option.dataset.title || '';
        document.getElementById('selectedPrice').value = option.dataset.price || '';
        document.getElementById('selectedLocation').value = option.dataset.location || '';
    });

    // Generate time slots dynamically
    const times = ["9:00 AM", "10:00 AM", "11:00 AM", "12:00 PM", "1:00 PM", "2:00 PM", "3:00 PM", "4:00 PM", "5:00 PM"];
    const container = document.getElementById('timeSlots');
    times.forEach(time => {
        const col = document.createElement('div');
        col.className = 'col-6 mb-2';
        const btnSlot = document.createElement('button');
        btnSlot.type = 'button';
        btnSlot.className = 'btn btn-outline-primary w-100 fw-bold time-slot';
        btnSlot.textContent = time;
        btnSlot.addEventListener('click', function() {
            document.querySelectorAll('.time-slot').forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-outline-primary');
            });
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary');
            document.getElementById('time').value = this.textContent;
        });
        col.appendChild(btnSlot);
        container.appendChild(col);
    });
</script>