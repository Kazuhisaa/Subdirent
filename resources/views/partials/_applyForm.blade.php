<div class="container my-3">
    <form id="applyForm" enctype="multipart/form-data" method="POST" action="{{ route('applications.store') }}">
        @csrf
        <div class="card shadow-sm p-4">
            <div class="card-header bg-success text-white mb-4">
                <h3 class="fw-bold mb-0">Apply to Rent</h3>
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

            <!-- Applicant Info -->
            <div class="mb-3">
                <label for="surname" class="form-label">Surname</label>
                <input type="text" id="surname" name="surname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" id="firstName" name="first_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="middleName" class="form-label">Middle Name</label>
                <input type="text" id="middleName" name="middle_name" class="form-control">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contact" class="form-label">Contact Number</label>
                <input type="text" id="contact" name="contact" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="idUpload" class="form-label">Upload ID</label>
                <input type="file" id="idUpload" name="id_upload" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="salary" class="form-label">Salary</label>
                <input type="text" id="salary" name="salary" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="leaseStart" class="form-label">Preferred Lease Start</label>
                <input type="date" id="leaseStart" name="lease_start" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="leaseDuration" class="form-label">Lease Duration (months)</label>
                <input type="number" id="leaseDuration" name="lease_duration" class="form-control" min="1" required>
            </div>

            <button type="submit" class="btn btn-success w-100 mt-3">Submit Application</button>
            <button type="button" class="btn btn-outline-secondary w-100 mt-2" data-bs-dismiss="modal">⬅ Back</button>
        </div>
    </form>

    <div id="successMsg" class="alert alert-success mt-4 d-none">
        ✅ Thank you! Your application has been submitted. We’ll contact you soon.
    </div>
</div>

<!-- Add this script at the end of your form partial -->
<script>
document.getElementById('applyForm').addEventListener('submit', function(e) {
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
</script>
