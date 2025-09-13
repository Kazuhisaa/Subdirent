<div class="container my-3">
    <form id="bookingForm">
        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-6">
                <div class="border rounded p-4 h-100 shadow-sm bg-white">
                    <h5 class="fw-bold mb-3">Property Summary</h5>
                    <img id="unitImg" src="{{ asset('images/unit01.jpg') }}" class="img-fluid rounded mb-3" alt="Unit">
                    <p id="unitDescription" class="text-dark mb-3">-</p>

                    <p class="mb-1 fw-bold text-dark" id="unitTitle">Unit Title</p>
                    <p class="mb-1 text-dark" id="unitName">Unit</p>
                    <p class="text-success fw-bold fs-5" id="unitPrice">₱0/month</p>

                    <h6 class="fw-bold mt-4 mb-2">Select Date</h6>
                    <input type="date" id="date" class="form-control form-control-lg" required>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-6">
                <!-- Time Slots -->
                <div class="border rounded p-4 mb-4 shadow-sm bg-white">
                    <h5 class="fw-bold mb-3">Available Time Slots</h5>
                    <div class="row g-2" id="timeSlots"></div>
                    <input type="hidden" id="time" required>
                </div>

                <!-- Contact + Booking Summary -->
                <div class="border rounded p-4 shadow-sm bg-white d-flex flex-column">
                    <h5 class="fw-bold mb-3">Contact Information</h5>
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" class="form-control form-control-lg" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-control form-control-lg" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact" class="form-label">Phone Number</label>
                        <input type="text" id="contact" class="form-control form-control-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="notes" class="form-label">Additional Notes (Optional)</label>
                        <textarea id="notes" class="form-control form-control-lg" rows="2"></textarea>
                    </div>

                    <h6 class="fw-bold mb-3">Booking Summary</h6>
                    <div class="bg-white p-3 rounded mb-4" id="bookingSummary">
                        <p class="mb-1 text-dark"><strong>Title:</strong> <span id="summaryTitle">-</span></p>
                        <p class="mb-1 text-dark"><strong>Unit:</strong> <span id="summaryUnit">-</span></p>
                        <p class="mb-1 text-dark"><strong>Price:</strong> <span id="summaryPrice">₱0/month</span></p>
                        <p class="mb-1 text-dark"><strong>Date:</strong> <span id="summaryDate">-</span></p>
                        <p class="mb-1 text-dark"><strong>Time:</strong> <span id="summaryTime">-</span></p>
                        <p class="mb-1 text-dark"><strong>Description:</strong> <span id="summaryDescription">-</span></p>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-auto">Confirm Booking</button>
                    <button type="button" class="btn btn-outline-secondary mt-2" data-bs-dismiss="modal">Back</button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .time-slot.btn-primary {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: white !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const times = ["9:00 AM","10:00 AM","11:00 AM","12:00 PM","1:00 PM","2:00 PM","3:00 PM","4:00 PM","5:00 PM"];

    const unitModal = document.getElementById('unitModal');

    // When modal is shown
    unitModal.addEventListener('shown.bs.modal', function () {

        // Populate property info from localStorage
        const title = localStorage.getItem("selectedTitle") || "-";
        const unit = localStorage.getItem("selectedUnit") || "-";
        const price = localStorage.getItem("selectedPrice") || "₱0/month";
        const description = localStorage.getItem("selectedDescription") || "-";

        const modalBody = document.getElementById('unitModalBody');

        modalBody.querySelector('#unitTitle').textContent = title;
        modalBody.querySelector('#unitName').textContent = unit;
        modalBody.querySelector('#unitPrice').textContent = price;
        modalBody.querySelector('#unitDescription').textContent = description;

        modalBody.querySelector('#summaryTitle').textContent = title;
        modalBody.querySelector('#summaryUnit').textContent = unit;
        modalBody.querySelector('#summaryPrice').textContent = price;
        modalBody.querySelector('#summaryDescription').textContent = description;
        modalBody.querySelector('#summaryDate').textContent = "-";
        modalBody.querySelector('#summaryTime').textContent = "-";

        // Generate time slots
        const container = modalBody.querySelector('#timeSlots');
        container.innerHTML = '';
        times.forEach(time => {
            const col = document.createElement('div');
            col.className = 'col-6 mb-2';
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-outline-primary w-100 fw-bold time-slot';
            btn.textContent = time;
            col.appendChild(btn);
            container.appendChild(col);
        });
    });

    // Event delegation for time slots
    document.getElementById('unitModal').addEventListener('click', function(e) {
        if(e.target && e.target.classList.contains('time-slot')){
            // Reset all buttons
            document.querySelectorAll('.time-slot').forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-outline-primary');
            });

            // Highlight clicked button
            e.target.classList.remove('btn-outline-primary');
            e.target.classList.add('btn-primary');

            // Update hidden input and summary
            const form = e.target.closest('form');
            form.querySelector('#time').value = e.target.textContent;
            form.querySelector('#summaryTime').textContent = e.target.textContent;
        }
    });

    // Update date picker summary
    document.getElementById('unitModal').addEventListener('change', function(e) {
        if(e.target && e.target.id === 'date'){
            const form = e.target.closest('form');
            form.querySelector('#summaryDate').textContent = e.target.value;
        }
    });
});
</script>
 