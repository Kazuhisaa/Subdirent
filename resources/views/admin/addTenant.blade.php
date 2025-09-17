@extends('admin.admin')

@section('content')
<div class="container py-4">
  <h2 class="mb-4">🧑‍💼 Admin - Manage Tenants</h2>

  <!-- Add Tenant Button -->
  <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addTenantModal">
    Add Tenant
  </button>

  <!-- Add Tenant Modal -->
  <div class="modal fade" id="addTenantModal" tabindex="-1" aria-labelledby="addTenantModalLabel">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="addTenantModalLabel">Add New Tenant</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="addTenantForm" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">First Name</label>
                <input type="text" class="form-control" name="first_name" required>
              </div>
              <div class="col-md-4">
                <label class="form-label">Middle Name</label>
                <input type="text" class="form-control" name="middle_name">
              </div>
              <div class="col-md-4">
                <label class="form-label">Last Name</label>
                <input type="text" class="form-control" name="last_name" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Contact</label>
                <input type="text" class="form-control" name="contact" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Location</label>
                <select class="form-select" id="locationSelect" required>
                  <option value="">-- Select Location --</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">House / Unit</label>
                <select class="form-select" name="unit_id" id="unitSelect" required>
                  <option value="">-- Select Unit --</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Monthly Rent</label>
                <input type="number" class="form-control" name="monthly_rent" step="0.01" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Lease Start</label>
                <input type="date" class="form-control" name="lease_start" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Lease End</label>
                <input type="date" class="form-control" name="lease_end" required>
              </div>
              <div class="col-md-12">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="notes" rows="3"></textarea>
              </div>
              <div class="col-md-12">
                <label class="form-label">Upload Image</label>
                <input type="file" class="form-control" name="image" accept="image/*">
              </div>
            </div>
            <div class="text-end mt-3">
              <button type="submit" class="btn btn-primary">💾 Save Tenant</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Tenants Table -->
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">All Tenants</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="tenantsTable">
          <thead class="table-light">
            <tr>
              <th>Full Name</th>
              <th>Email</th>
              <th>Contact</th>
              <th>House</th>
              <th>Monthly Rent</th>
              <th>Lease Start</th>
              <th>Lease End</th>
              <th>Image</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="tenantsBody"></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- View Tenant Modal -->
  <div class="modal fade" id="viewTenantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title">Tenant Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="viewTenantBody"></div>
      </div>
    </div>
  </div>

  <!-- Edit Tenant Modal -->
  <div class="modal fade" id="editTenantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title">Edit Tenant</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="editTenantForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="edit_id">
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">First Name</label>
                <input type="text" class="form-control" name="first_name" id="edit_first_name" required>
              </div>
              <div class="col-md-4">
                <label class="form-label">Middle Name</label>
                <input type="text" class="form-control" name="middle_name" id="edit_middle_name">
              </div>
              <div class="col-md-4">
                <label class="form-label">Last Name</label>
                <input type="text" class="form-control" name="last_name" id="edit_last_name" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="edit_email" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Contact</label>
                <input type="text" class="form-control" name="contact" id="edit_contact" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">House / Unit</label>
                <select class="form-select" name="unit_id" id="edit_unit" required>
                  <option value="">-- Select Unit --</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Monthly Rent</label>
                <input type="number" class="form-control" name="monthly_rent" id="edit_monthly_rent" step="0.01" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Lease Start</label>
                <input type="date" class="form-control" name="lease_start" id="edit_lease_start" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Lease End</label>
                <input type="date" class="form-control" name="lease_end" id="edit_lease_end" required>
              </div>
              <div class="col-md-12">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="notes" rows="3" id="edit_notes"></textarea>
              </div>
              <div class="col-md-12">
                <label class="form-label">Upload Image</label>
                <input type="file" class="form-control" name="image" id="edit_image" accept="image/*">
              </div>
            </div>
            <div class="text-end mt-3">
              <button type="submit" class="btn btn-warning">✏️ Update Tenant</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  let allUnits = [];
  const tenantsBody = document.getElementById('tenantsBody');
  const unitSelect = document.getElementById('unitSelect');
  const editUnitSelect = document.getElementById('edit_unit');
  const locationSelect = document.getElementById('locationSelect');

  function loadUnits() {
    fetch('/api/units/allunits')
      .then(res => res.json())
      .then(units => {
        allUnits = units;

        // Unique locations
        let locations = [...new Set(units.map(u => u.location))];
        locationSelect.innerHTML = '<option value="">-- Select Location --</option>';
        locations.forEach(loc => {
          locationSelect.innerHTML += `<option value="${loc}">${loc}</option>`;
        });
      });
  }

  locationSelect.addEventListener('change', function() {
    let selectedLocation = this.value;

    unitSelect.innerHTML = '<option value="">-- Select Unit --</option>';

    if (selectedLocation) {
      let filteredUnits = allUnits.filter(u => u.location === selectedLocation);

      filteredUnits.forEach(u => {
        unitSelect.innerHTML += `<option value="${u.id}" data-price="${u.price}">${u.title}</option>`;
      });
    }
  });

  unitSelect.addEventListener('change', function() {
    let selected = this.options[this.selectedIndex];
    let price = selected.getAttribute('data-price');
    if (price) {
      document.querySelector('input[name="monthly_rent"]').value = price;
    }
  });

  // Load tenants
  function loadTenants() {
    fetch('/api/tenants/allTenant')
      .then(res => res.json())
      .then(tenants => {
        tenantsBody.innerHTML = '';
        tenants.forEach(t => {
          tenantsBody.innerHTML += `
<tr>
<td>${t.first_name} ${t.middle_name ?? ''} ${t.last_name}</td>
<td>${t.email}</td>
<td>${t.contact}</td>
<td>${t.unit ? t.unit.title : '—'}</td>
<td>₱${t.monthly_rent}</td>
<td>${t.lease_start}</td>
<td>${t.lease_end}</td>
<td>${t.image ? `<img src="/uploads/tenants/${t.image}" width="60" class="rounded">` : '—'}</td>
<td>
<button class="btn btn-sm btn-info" onclick="viewTenant(${t.id})">View</button>
<button class="btn btn-sm btn-warning" onclick="editTenant(${t.id})">Edit</button>
<button class="btn btn-sm btn-danger" onclick="deleteTenant(${t.id})">Delete</button>
</td>
</tr>`;
        });
      });
  }

  // Add tenant
  document.getElementById('addTenantForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    fetch('/api/tenants/newTenant', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        alert('✅ Tenant added successfully!');
        this.reset();
        loadTenants();
        bootstrap.Modal.getInstance(document.getElementById('addTenantModal')).hide();
      })
      .catch(err => alert('❌ Error adding tenant'));
  });

  // View tenant
  function viewTenant(id) {
    fetch(`/api/tenants/findTenant/${id}`)
      .then(res => res.json())
      .then(t => {
        document.getElementById('viewTenantBody').innerHTML = `
<div class="row">
<div class="col-md-4">
${t.image ? `<img src="/uploads/tenants/${t.image}" class="img-fluid rounded">` : '<div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height:200px;">No Image</div>'}
</div>
<div class="col-md-8">
<h4>${t.first_name} ${t.middle_name ?? ''} ${t.last_name}</h4>
<p><strong>Email:</strong> ${t.email}</p>
<p><strong>Contact:</strong> ${t.contact}</p>
<p><strong>House:</strong> ${t.unit ? t.unit.title : '—'}</p>
<p><strong>Monthly Rent:</strong> ₱${t.monthly_rent}</p>
<p><strong>Lease Start:</strong> ${t.lease_start}</p>
<p><strong>Lease End:</strong> ${t.lease_end}</p>
<p><strong>Notes:</strong><br>${t.notes ?? '-'}</p>
</div>
</div>`;
        new bootstrap.Modal(document.getElementById('viewTenantModal')).show();
      });
  }

  // Edit tenant
  function editTenant(id) {
    fetch(`/api/tenants/findTenant/${id}`)
      .then(res => res.json())
      .then(t => {
        document.getElementById('edit_id').value = t.id;
        document.getElementById('edit_first_name').value = t.first_name;
        document.getElementById('edit_middle_name').value = t.middle_name ?? '';
        document.getElementById('edit_last_name').value = t.last_name;
        document.getElementById('edit_email').value = t.email;
        document.getElementById('edit_contact').value = t.contact;
        document.getElementById('edit_unit').value = t.unit_id;
        document.getElementById('edit_monthly_rent').value = t.monthly_rent;
        document.getElementById('edit_lease_start').value = t.lease_start;
        document.getElementById('edit_lease_end').value = t.lease_end;
        document.getElementById('edit_notes').value = t.notes ?? '';

        new bootstrap.Modal(document.getElementById('editTenantModal')).show();
      });
  }

  // Update tenant
  document.getElementById('editTenantForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let id = document.getElementById('edit_id').value;
    let formData = new FormData(this);
    formData.append('_method', 'PUT');

    fetch(`/api/tenants/updateTenant/${id}`, {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        alert('✏️ Tenant updated successfully!');
        loadTenants();
        bootstrap.Modal.getInstance(document.getElementById('editTenantModal')).hide();
      })
      .catch(err => alert('❌ Error updating tenant'));
  });

  // Delete tenant
  function deleteTenant(id) {
    if (confirm('Are you sure you want to delete this tenant?')) {
      fetch(`/api/tenants/deleteTenant/${id}`, {
          method: 'DELETE'
        })
        .then(res => res.json())
        .then(data => {
          alert('🗑️ Tenant deleted');
          loadTenants();
        });
    }
  }

  // Initial load
  loadUnits();
  loadTenants();
</script>
@endsection