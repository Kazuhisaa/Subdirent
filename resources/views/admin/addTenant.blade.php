@extends('admin.admin')

@section('content')
<div class="container py-4">
  <h2 class="mb-4">👥 Admin - Manage Tenants</h2>

  <!-- Add Tenant Button -->
  <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addTenantModal">➕ Add Tenant</button>

  <!-- Tenants Table -->
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">All Tenants</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="tenantsTable">
          <thead class="table-light">
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Unit</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="tenantsBody"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Add Tenant Modal -->
<div class="modal fade" id="addTenantModal" tabindex="-1" aria-labelledby="addTenantModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addTenantModalLabel">Add New Tenant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="addTenantForm">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Name</label><input type="text" class="form-control" name="name" required></div>
            <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" name="email" required></div>
            <div class="col-md-6"><label class="form-label">Phone</label><input type="text" class="form-control" name="phone"></div>
            <div class="col-md-6"><label class="form-label">Unit</label><input type="text" class="form-control" name="unit"></div>
            <div class="col-md-12"><label class="form-label">Notes</label><textarea class="form-control" name="notes" rows="3"></textarea></div>
          </div>
          <div class="text-end mt-3">
            <button type="submit" class="btn btn-primary">Save Tenant</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Tenant Modal -->
<div class="modal fade" id="editTenantModal" tabindex="-1" aria-labelledby="editTenantModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="editTenantModalLabel">Edit Tenant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="editTenantForm">
          <input type="hidden" id="edit_tenant_id" name="id">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Name</label><input type="text" class="form-control" id="edit_name" name="name" required></div>
            <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" id="edit_email" name="email" required></div>
            <div class="col-md-6"><label class="form-label">Phone</label><input type="text" class="form-control" id="edit_phone" name="phone"></div>
            <div class="col-md-6"><label class="form-label">Unit</label><input type="text" class="form-control" id="edit_unit" name="unit"></div>
            <div class="col-md-12"><label class="form-label">Notes</label><textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea></div>
          </div>
          <div class="text-end mt-3">
            <button type="submit" class="btn btn-warning">Update Tenant</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
const tenantsBody = document.getElementById('tenantsBody');

function loadTenants() {
  fetch('/api/tenants/all')
    .then(res => res.json())
    .then(tenants => {
      tenantsBody.innerHTML = '';
      tenants.forEach(t => {
        tenantsBody.innerHTML += `
<tr>
  <td>${t.name}</td>
  <td>${t.email}</td>
  <td>${t.phone ?? '-'}</td>
  <td>${t.unit ?? '-'}</td>
  <td>${t.active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'}</td>
  <td>
    <button class="btn btn-sm btn-warning" onclick="editTenant(${t.id})">Edit</button>
    <button class="btn btn-sm btn-danger" onclick="deleteTenant(${t.id})">Delete</button>
  </td>
</tr>`;
      });
    });
}

// Add Tenant
document.getElementById('addTenantForm').addEventListener('submit', function(e){
  e.preventDefault();
  let formData = new FormData(this);
  fetch('/api/tenants/new', { method:'POST', body:formData })
    .then(res=>res.json())
    .then(()=>{ alert('✅ Tenant added'); this.reset(); loadTenants(); bootstrap.Modal.getInstance(document.getElementById('addTenantModal')).hide(); })
    .catch(()=>alert('❌ Error adding tenant'));
});

// Delete Tenant
function deleteTenant(id){
  if(confirm('Delete this tenant?')){
    fetch(`/api/tenants/delete/${id}`, { method:'DELETE' })
      .then(()=>{ alert('🗑️ Tenant deleted'); loadTenants(); });
  }
}

// Edit Tenant
function editTenant(id){
  fetch(`/api/tenants/find/${id}`)
    .then(res=>res.json())
    .then(t=>{
      document.getElementById('edit_tenant_id').value = t.id;
      document.getElementById('edit_name').value = t.name;
      document.getElementById('edit_email').value = t.email;
      document.getElementById('edit_phone').value = t.phone ?? '';
      document.getElementById('edit_unit').value = t.unit ?? '';
      document.getElementById('edit_notes').value = t.notes ?? '';
      new bootstrap.Modal(document.getElementById('editTenantModal')).show();
    });
}

// Submit Edit
document.getElementById('editTenantForm').addEventListener('submit', function(e){
  e.preventDefault();
  let id = document.getElementById('edit_tenant_id').value;
  let formData = new FormData(this);
  formData.append('_method','PUT');
  fetch(`/api/tenants/update/${id}`, { method:'POST', body:formData })
    .then(()=>{ alert('✏️ Tenant updated'); loadTenants(); bootstrap.Modal.getInstance(document.getElementById('editTenantModal')).hide(); })
    .catch(()=>alert('❌ Error updating tenant'));
});

// Initial load
loadTenants();
</script>
@endsection
