@extends('admin.admin')

@section('content')
<div class="container-fluid py-4">
  <h2 class="mb-4"><i class="bi bi-building me-2"></i> Admin - Manage Units</h2>

  <!-- Add Unit Button -->
  <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addUnitModal">
    <i class="bi bi-plus-circle me-1"></i> Add Unit
  </button>

  <!-- Units Table -->
  <div class="card shadow-sm mx-auto" style="max-width: 1800px;">
    <div class="card-header bg-secondary text-white text-center">All Units</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle text-center" id="unitsTable">
          <thead class="table-success">
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

  <!-- Add Unit Modal -->
  <div class="modal fade" id="addUnitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Add New Unit</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="addUnitForm" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control">
              </div>
              <div class="col-md-4">
                <label class="form-label">Bedrooms</label>
                <input type="number" name="bedrooms" class="form-control">
              </div>
              <div class="col-md-4">
                <label class="form-label">Bathrooms</label>
                <input type="number" name="bathrooms" class="form-control">
              </div>
              <div class="col-md-4">
                <label class="form-label">Floor Area</label>
                <input type="number" name="floor_area" class="form-control">
              </div>
              <div class="col-md-6">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control">
              </div>
              <div class="col-md-6">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control">
              </div>
              <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
              </div>
            </div>
            <div class="text-end mt-3">
              <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Unit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- View Unit Modal -->
  <div class="modal fade" id="viewUnitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title">Unit Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="viewUnitBody"></div>
      </div>
    </div>
  </div>

  <!-- Edit Unit Modal -->
  <div class="modal fade" id="editUnitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title">Edit Unit</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="editUnitForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="editUnitId" name="id">
            <div class="mb-3">
              <label class="form-label">Title</label>
              <input type="text" name="title" id="editTitle" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Location</label>
              <input type="text" name="location" id="editLocation" class="form-control">
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Bedrooms</label>
                <input type="number" name="bedrooms" id="editBedrooms" class="form-control">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Bathrooms</label>
                <input type="number" name="bathrooms" id="editBathrooms" class="form-control">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Floor Area</label>
                <input type="number" name="floor_area" id="editFloorArea" class="form-control">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Price</label>
              <input type="number" step="0.01" name="price" id="editPrice" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea name="description" id="editDescription" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Image</label>
              <input type="file" name="image" class="form-control">
            </div>
            <button type="submit" class="btn btn-warning">Update Unit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


@section('scripts')
<script>
  const unitsBody = document.getElementById('unitsBody');

  // Load Units
  function loadUnits() {
    fetch('/api/units/allunits')
      .then(res => res.json())
      .then(units => {
        unitsBody.innerHTML = '';
        units.forEach(u => {
          unitsBody.innerHTML += `
<tr>
  <td>${u.title}</td>
  <td>${u.location ?? ''}</td>
  <td>${u.bedrooms ?? '-'}</td>
  <td>${u.bathrooms ?? '-'}</td>
  <td>${u.floor_area ?? '-'}</td>
  <td>₱${u.price}</td>
  <td>${u.image ? `<img src="/uploads/units/${u.image}" width="60" class="rounded">` : '—'}</td>
  <td>
    <button class="btn btn-sm btn-info" onclick="viewUnit(${u.id})">View</button>
    <button class="btn btn-sm btn-warning" onclick="editUnit(${u.id})">Edit</button>
    <button class="btn btn-sm btn-danger" onclick="deleteUnit(${u.id})">Delete</button>
  </td>
</tr>`;
        });
      });
  }

  // Add Unit
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
        bootstrap.Modal.getInstance(document.getElementById('addUnitModal')).hide();
      })
      .catch(err => alert('❌ Error adding unit'));
  });

  // View Unit
  function viewUnit(id) {
    fetch(`/api/units/findunit/${id}`)
      .then(res => res.json())
      .then(u => {
        document.getElementById('viewUnitBody').innerHTML = `
<p><strong>Title:</strong> ${u.title}</p>
<p><strong>Location:</strong> ${u.location ?? ''}</p>
<p><strong>Bedrooms:</strong> ${u.bedrooms ?? ''}</p>
<p><strong>Bathrooms:</strong> ${u.bathrooms ?? ''}</p>
<p><strong>Floor Area:</strong> ${u.floor_area ?? ''}</p>
<p><strong>Price:</strong> ₱${u.price ?? ''}</p>
${u.image ? `<img src="/uploads/units/${u.image}" class="img-fluid rounded">` : ''}
        `;
        new bootstrap.Modal(document.getElementById('viewUnitModal')).show();
      });
  }

  // Edit Unit
  function editUnit(id) {
    fetch(`/api/units/findunit/${id}`)
      .then(res => res.json())
      .then(u => {
        document.getElementById('editUnitId').value = u.id;
        document.getElementById('editTitle').value = u.title;
        document.getElementById('editLocation').value = u.location ?? '';
        document.getElementById('editBedrooms').value = u.bedrooms ?? '';
        document.getElementById('editBathrooms').value = u.bathrooms ?? '';
        document.getElementById('editFloorArea').value = u.floor_area ?? '';
        document.getElementById('editPrice').value = u.price ?? '';
        document.getElementById('editDescription').value = u.description ?? '';

        new bootstrap.Modal(document.getElementById('editUnitModal')).show();
      });
  }

  // Update Unit
  document.getElementById('editUnitForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let id = document.getElementById('editUnitId').value;
    let formData = new FormData(this);
    formData.append('_method', 'PUT');

    fetch(`/api/units/updateUnit/${id}`, {
        method: 'POST',
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

  // Delete Unit
  function deleteUnit(id) {
    if (confirm('Are you sure you want to delete this unit?')) {
      fetch(`/api/units/deleteunit/${id}`, {
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
@endsection