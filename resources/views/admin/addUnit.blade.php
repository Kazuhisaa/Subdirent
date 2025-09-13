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
  @include('admin.admin')
  <div class="container py-4">
    <h2 class="mb-4">🏠 Admin - Manage Units</h2>

    <!-- Button to open Add Unit modal -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addUnitModal">
      Add Unit
    </button>

    <!-- Add Unit Modal -->
    <div class="modal fade" id="addUnitModal" tabindex="-1" aria-labelledby="addUnitModalLabel">
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
                  <input type="file" class="form-control" name="image" accept="image/*">
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
                <th>Title</th>
                <th>Location</th>
                <th>Bedrooms</th>
                <th>Bathrooms</th>
                <th>Floor Area</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="unitsBody"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="viewUnitModal" tabindex="-1" aria-labelledby="viewUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title" id="viewUnitModalLabel">Unit Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="viewUnitBody">
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Unit Modal -->
  <div class="modal fade" id="editUnitModal" tabindex="-1" aria-labelledby="editUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title" id="editUnitModalLabel">Edit Unit</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="editUnitForm" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit_id">

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" name="title" id="edit_title" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Location</label>
                <input type="text" class="form-control" name="location" id="edit_location" required>
              </div>
              <div class="col-md-4">
                <label class="form-label">Bedrooms</label>
                <input type="number" class="form-control" name="bedrooms" id="edit_bedrooms">
              </div>
              <div class="col-md-4">
                <label class="form-label">Bathrooms</label>
                <input type="number" class="form-control" name="bathrooms" id="edit_bathrooms">
              </div>
              <div class="col-md-4">
                <label class="form-label">Floor Area (sqm)</label>
                <input type="number" class="form-control" name="floor_area" id="edit_floor_area">
              </div>
              <div class="col-md-6">
                <label class="form-label">Price</label>
                <input type="number" class="form-control" name="price" step="0.01" id="edit_price" required>
              </div>
              <div class="col-md-12">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3" id="edit_description" required></textarea>
              </div>
              <div class="col-md-12">
                <label class="form-label">Upload Image (optional)</label>
                <input type="file" class="form-control" name="image" id="edit_image" accept="image/*">
                <small class="text-muted">Leave blank if you don’t want to change image.</small>
              </div>
              <div class="form-check mb-3 mt-2">
                <input class="form-check-input" type="checkbox" id="edit_is_rented" name="is_rented">
                <label class="form-check-label" for="edit_is_rented">Already Rented</label>
              </div>
            </div>

            <div class="text-end mt-3">
              <button type="submit" class="btn btn-warning">Update Unit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const unitsBody = document.getElementById('unitsBody');

    // Load all units from API
    function loadUnits() {
      fetch('/api/units/allunits')
        .then(res => res.json())
        .then(units => {
          unitsBody.innerHTML = '';
          units.forEach(unit => {
            unitsBody.innerHTML += `
    <tr>
      <td>${unit.title}</td>
      <td>${unit.location}</td>
      <td>${unit.bedrooms ?? '-'}</td>
      <td>${unit.bathrooms ?? '-'}</td>
      <td>${unit.floor_area ?? '-'}</td>
      <td>₱${unit.price}</td>
      <td>
        ${unit.image 
          ? `<img src="/uploads/units/${unit.image}" alt="Unit Image" width="60" class="rounded">`
          : '—'}
      </td>
      <td>
        <button class="btn btn-sm btn-info" onclick="viewUnit(${unit.id})">View</button>
        <button class="btn btn-sm btn-warning" onclick="editUnit(${unit.id})">Edit</button>
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

      fetch('/api/units/newunit', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          alert('✅ Unit added successfully!');
          this.reset();
          loadUnits();
          const addUnitModal = bootstrap.Modal.getInstance(document.getElementById('addUnitModal'));
          addUnitModal.hide();
        })
        .catch(err => alert('❌ Error adding unit'));
    });

    // Delete unit
    function deleteUnit(id) {
      if (confirm('Are you sure you want to delete this unit?')) {
        fetch(`/api/units/deleteunit/ ${id}`, {
            method: 'DELETE'
          })
          .then(res => res.json())
          .then(data => {
            alert('🗑️ Unit deleted');
            loadUnits();
          });
      }
    }

    function viewUnit(id) {
      fetch(`/api/units/findunit/${id}`)
        .then(res => res.json())
        .then(unit => {
          const viewUnitBody = document.getElementById('viewUnitBody');
          viewUnitBody.innerHTML = `
      <div class="row">
        <div class="col-md-4">
          ${unit.image 
            ? `<img src="/uploads/units/${unit.image}" alt="Unit Image" class="img-fluid rounded">`
            : '<div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height:200px;">No Image</div>'}
        </div>
        <div class="col-md-8">
          <h4>${unit.title}</h4>
          <p><strong>Location:</strong> ${unit.location}</p>
          <p><strong>Bedrooms:</strong> ${unit.bedrooms ?? '-'}</p>
          <p><strong>Bathrooms:</strong> ${unit.bathrooms ?? '-'}</p>
          <p><strong>Floor Area:</strong> ${unit.floor_area ?? '-'} sqm</p>
          <p><strong>Price:</strong> ₱${unit.price}</p>
          <p><strong>Description:</strong><br>${unit.description}</p>
          <p><strong>Status:</strong> ${unit.is_rented ? '<span class="badge bg-danger">Rented</span>' : '<span class="badge bg-success">Available</span>'}</p>
        </div>
      </div>
    `;
          const viewUnitModal = new bootstrap.Modal(document.getElementById('viewUnitModal'));
          viewUnitModal.show();
        });
    }

    // Load unit data into edit form
    function editUnit(id) {
      fetch(`/api/units/findunit/${id}`)
        .then(res => res.json())
        .then(unit => {
          document.getElementById('edit_id').value = unit.id;
          document.getElementById('edit_title').value = unit.title;
          document.getElementById('edit_location').value = unit.location;
          document.getElementById('edit_bedrooms').value = unit.bedrooms ?? '';
          document.getElementById('edit_bathrooms').value = unit.bathrooms ?? '';
          document.getElementById('edit_floor_area').value = unit.floor_area ?? '';
          document.getElementById('edit_price').value = unit.price;
          document.getElementById('edit_description').value = unit.description;
          document.getElementById('edit_is_rented').checked = unit.is_rented == 1;

          new bootstrap.Modal(document.getElementById('editUnitModal')).show();
        })
        .catch(err => alert('❌ Error loading unit data'));
    }

    // Submit edit form
    document.getElementById('editUnitForm').addEventListener('submit', function(e) {
      e.preventDefault();
      let id = document.getElementById('edit_id').value;
      let formData = new FormData(this);
      formData.append('_method', 'PUT'); // since fetch doesn’t support PUT with FormData directly

      fetch(`/api/units/updateUnit/${id}`, {
          method: 'POST', // Laravel will treat it as PUT dahil sa _method
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          alert('✏️ Unit updated successfully!');
          loadUnits();
          bootstrap.Modal.getInstance(document.getElementById('editUnitModal')).hide();
        })
        .catch(err => alert('❌ Error updating unit'));
    });


    // Initial load
    loadUnits();
  </script>
  <!-- View Unit Modal -->



</body>

</html>