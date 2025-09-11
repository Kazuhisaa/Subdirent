<!-- resources/views/admin/addUnit.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Manage Units</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

  <div class="container py-4">
    <h2 class="mb-4">🏠 Admin - Manage Units</h2>

    <!-- Button to open Add Unit modal -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addUnitModal">
      Add Unit
    </button>

    <!-- Add Unit Modal -->
    <div class="modal fade" id="addUnitModal" tabindex="-1" aria-labelledby="addUnitModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="addUnitModalLabel">Add New Unit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="addUnitForm" enctype="multipart/form-data">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Title</label>
                  <input type="text" class="form-control" name="title" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Location</label>
                  <input type="text" class="form-control" name="location" required>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Bedrooms</label>
                  <input type="number" class="form-control" name="bedrooms">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Bathrooms</label>
                  <input type="number" class="form-control" name="bathrooms">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Floor Area (sqm)</label>
                  <input type="number" class="form-control" name="floor_area">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Price</label>
                  <input type="number" class="form-control" name="price" step="0.01" required>
                </div>
                <div class="col-md-12">
                  <label class="form-label">Description</label>
                  <textarea class="form-control" name="description" rows="3" required></textarea>
                </div>
                <div class="col-md-12">
                  <label class="form-label">Upload Image</label>
                  <input type="file" class="form-control" name="unit_image" accept="image/*">
                </div>
                <div class="form-check mb-3 mt-2">
                  <input class="form-check-input" type="checkbox" id="is_rented" name="is_rented">
                  <label class="form-check-label" for="is_rented">Already Rented</label>
                </div>
              </div>
              <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">Save Unit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Units Table -->
    <div class="card shadow-sm">
      <div class="card-header bg-secondary text-white">All Units</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle" id="unitsTable">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Location</th>
                <th>Bedrooms</th>
                <th>Bathrooms</th>
                <th>Floor Area</th>
                <th>Price</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="unitsBody">
              {{-- rows loaded by JS --}}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const unitsBody = document.getElementById('unitsBody');

    // Load all units from API
    function loadUnits() {
      fetch('/api/units')
        .then(res => res.json())
        .then(units => {
          unitsBody.innerHTML = '';
          units.forEach(unit => {
            unitsBody.innerHTML += `
          <tr>
            <td>${unit.id}</td>
            <td>${unit.title}</td>
            <td>${unit.location}</td>
            <td>${unit.bedrooms ?? '-'}</td>
            <td>${unit.bathrooms ?? '-'}</td>
            <td>${unit.floor_area ?? '-'}</td>
            <td>₱${unit.price}</td>
            <td>
              <button class="btn btn-sm btn-warning">Edit</button>
              <button class="btn btn-sm btn-danger" onclick="deleteUnit(${unit.id})">Delete</button>
            </td>
          </tr>
        `;
          });
        });
    }

    // Add new unit
    document.getElementById('addUnitForm').addEventListener('submit', function(e) {
      e.preventDefault();
      let formData = new FormData(this);

      fetch('/api/units', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          alert('✅ Unit added successfully!');
          this.reset();
          loadUnits();
          const addUnitModal = bootstrap.Modal.getInstance(document.getElementById('addUnitModal'));
          addUnitModal.hide(); // close modal
        })
        .catch(err => alert('❌ Error adding unit'));
    });

    // Delete unit
    function deleteUnit(id) {
      if (confirm('Are you sure you want to delete this unit?')) {
        fetch(`/api/units/${id}`, {
            method: 'DELETE'
          })
          .then(res => res.json())
          .then(data => {
            alert('🗑️ Unit deleted');
            loadUnits();
          });
      }
    }

    // Initial load
    loadUnits();
  </script>

</body>

</html>