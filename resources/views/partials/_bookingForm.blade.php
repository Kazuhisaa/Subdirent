<div class="container my-3">
    <form id="bookingForm" enctype="multipart/form-data" method="POST" action="{{ route('booking.store') }}">
        @csrf
        <div class="card shadow-sm p-4">
            <div class="card-header bg-primary text-white mb-4">
                <h3 class="fw-bold mb-0">Book a Unit</h3>
            </div>

            <!-- Unit Info -->
            <div class="mb-3">
                <label for="selectedTitle" class="form-label">Unit Title</label>
                <input type="text" id="selectedTitle" name="title" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label for="selectedUnit" class="form-label">Selected Unit</label>
                <input type="text" id="selectedUnit" name="unit" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label for="selectedPrice" class="form-label">Price</label>
                <input type="text" id="selectedPrice" name="price" class="form-control" readonly>
            </div>

            <!-- Date & Time -->
            <div class="mb-3">
                <label for="date" class="form-label">Select Date</label>
                <input type="date" id="date" name="date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Select Time Slot</label>
                <div class="row g-2" id="timeSlots"></div>
                <input type="hidden" id="time" name="time_slot" required>
            </div>

            <!-- Contact Info -->
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" id="name" name="full_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contact" class="form-label">Phone Number</label>
                <input type="text" id="contact" name="contact" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="notes" class="form-label">Additional Notes (Optional)</label>
                <textarea id="notes" name="notes" class="form-control" rows="2"></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">Confirm Booking</button>
            <button type="button" class="btn btn-outline-secondary w-100 mt-2" data-bs-dismiss="modal">⬅ Back</button>
        </div>
    </form>

    <div id="successMsg" class="alert alert-success mt-4 d-none">
        ✅ Your booking has been submitted successfully! We’ll contact you soon.
    </div>
</div>

<script>
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

// Generate time slots dynamically
const times = ["9:00 AM","10:00 AM","11:00 AM","12:00 PM","1:00 PM","2:00 PM","3:00 PM","4:00 PM","5:00 PM"];
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
