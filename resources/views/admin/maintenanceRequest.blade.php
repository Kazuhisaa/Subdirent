@extends('admin.admin')

@section('content')
<div class="container py-4">
  <h2 class="mb-4">🛠️ Admin - Maintenance Requests</h2>

  <!-- Maintenance Requests Table -->
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">All Requests</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="requestsTable">
          <thead class="table-light">
            <tr>
              <th>Tenant</th>
              <th>House</th>
              <th>Location</th>
              <th>Contact</th>
              <th>Request</th>
              <th>Image</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="requestsBody"></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- View Request Modal -->
  <div class="modal fade" id="viewRequestModal" tabindex="-1" aria-labelledby="viewRequestModalLabel">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title" id="viewRequestModalLabel">View Maintenance Request</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="viewRequestBody"></div>
      </div>
    </div>
  </div>

  <!-- Edit Request Modal -->
  <div class="modal fade" id="editRequestModal" tabindex="-1" aria-labelledby="editRequestModalLabel">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title" id="editRequestModalLabel">Edit Maintenance Request</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="editRequestForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="editRequestId" name="id">
            <div class="mb-3">
              <label class="form-label">Status</label>
              <select name="status" id="editStatus" class="form-control" required>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
              </select>
            </div>
            <button type="submit" class="btn btn-warning">Update Status</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  const requestsBody = document.getElementById('requestsBody');

  // Load all maintenance requests
  function loadRequests() {
    fetch('/api/maintenance/requests')
      .then(res => res.json())
      .then(requests => {
        requestsBody.innerHTML = '';
        requests.forEach(r => {
          const tenantName = r.tenant ? `${r.tenant.first_name} ${r.tenant.middle_name || ''} ${r.tenant.last_name}` : '—';
          const statusBadge = r.status == 'pending' ? 'warning' : r.status == 'in_progress' ? 'primary' : 'success';
          const imageHTML = r.image ? `<img src="/storage/${r.image}" width="60" class="rounded">` : '—';

          requestsBody.innerHTML += `
<tr>
  <td>${tenantName}</td>
  <td>${r.house}</td>
  <td>${r.location}</td>
  <td>${r.contact}</td>
  <td>${r.request}</td>
  <td>${imageHTML}</td>
  <td><span class="badge bg-${statusBadge}">${r.status.charAt(0).toUpperCase() + r.status.slice(1)}</span></td>
  <td>
    <button class="btn btn-sm btn-info" onclick="viewRequest(${r.id})">View</button>
    <button class="btn btn-sm btn-warning" onclick="editRequest(${r.id})">Edit</button>
    <button class="btn btn-sm btn-danger" onclick="deleteRequest(${r.id})">Delete</button>
  </td>
</tr>`;
        });
      })
      .catch(err => console.error('Error loading requests:', err));
  }

  // View Request
  function viewRequest(id) {
    fetch(`/api/maintenance/requests/${id}`)
      .then(res => res.json())
      .then(r => {
        const tenantName = r.tenant ? `${r.tenant.first_name} ${r.tenant.middle_name || ''} ${r.tenant.last_name}` : '—';
        document.getElementById('viewRequestBody').innerHTML = `
<p><strong>Tenant:</strong> ${tenantName}</p>
<p><strong>House:</strong> ${r.house}</p>
<p><strong>Location:</strong> ${r.location}</p>
<p><strong>Contact:</strong> ${r.contact}</p>
<p><strong>Request:</strong> ${r.request}</p>
<p><strong>Status:</strong> ${r.status}</p>
${r.image ? `<img src="/storage/${r.image}" class="img-fluid rounded">` : ''}`;
        new bootstrap.Modal(document.getElementById('viewRequestModal')).show();
      });
  }

  // Edit Request Status

  // Edit Request Status
  function editRequest(id) {
    fetch(`/api/maintenance/requests/${id}`)
      .then(res => res.json())
      .then(r => {
        document.getElementById('editRequestId').value = r.id;
        document.getElementById('editStatus').value = r.status;
        new bootstrap.Modal(document.getElementById('editRequestModal')).show();
      });
  }

  // Attach form listener once (outside editRequest)
  document.getElementById('editRequestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let id = document.getElementById('editRequestId').value;
    let formData = new FormData(this); // contains 'status'
    formData.append('_method', 'PATCH');

    fetch(`/api/maintenance/requests/${id}/status`, {
        method: 'post',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        alert('✅ Status updated!');
        loadRequests();
        bootstrap.Modal.getInstance(document.getElementById('editRequestModal')).hide();
      })
      .catch(err => alert('❌ Error updating status'));
  });




  // Delete Request
  function deleteRequest(id) {
    if (confirm('Are you sure you want to delete this request?')) {
      fetch(`/api/maintenance/requests/${id}`, {
          method: 'DELETE'
        })
        .then(res => res.json())
        .then(data => {
          alert('🗑️ Request deleted');
          loadRequests();
        });
    }
  }

  // Initial load
  loadRequests();
</script>
@endsection