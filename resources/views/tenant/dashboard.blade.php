@extends('layout.applys')

@section('content')
<div class="container my-4">
  <h1 class="mb-4 text-dark fw-bold">Tenant Dashboard</h1>

  <!-- Tabs -->
  <ul class="nav nav-tabs" id="tenantTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active text-dark" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
        type="button" role="tab">Profile</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link text-dark" id="house-tab" data-bs-toggle="tab" data-bs-target="#house"
        type="button" role="tab">House</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link text-dark" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments"
        type="button" role="tab">Payments</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link text-dark" id="lease-tab" data-bs-toggle="tab" data-bs-target="#lease"
        type="button" role="tab">Lease Agreement</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link text-dark" id="maintenance-tab" data-bs-toggle="tab" data-bs-target="#maintenance"
        type="button" role="tab">Maintenance</button>
    </li>
  </ul>

  <!-- Tab Contents -->
  <div class="tab-content mt-3" id="tenantTabsContent">
    <!-- Profile Tab -->
    <div class="tab-pane fade show active" id="profile" role="tabpanel">
      <div class="card p-3 bg-white shadow-sm">
        <h4 class="text-dark fw-bold">Profile Information</h4>
        <p class="text-dark"><strong>Name:</strong> {{ $tenant->first_name }} {{ $tenant->last_name }}</p>
        <p class="text-dark"><strong>Email:</strong> {{ $tenant->email }}</p>
        <p class="text-dark"><strong>Contact:</strong> {{ $tenant->contact }}</p>
      </div>
    </div>

    <!-- House Tab -->
    <div class="tab-pane fade" id="house" role="tabpanel">
      <div class="card p-3 bg-white shadow-sm">
        <h4 class="text-dark fw-bold">House Information</h4>
        @if($tenant->unit)
        <p class="text-dark"><strong>Title:</strong> {{ $tenant->unit->title }}</p>
        <p class="text-dark"><strong>Location:</strong> {{ $tenant->unit->location ?? 'N/A' }}</p>
        <p class="text-dark"><strong>Price:</strong> {{ $tenant->unit->price ?? 'N/A' }}</p>
        @else
        <p class="text-dark">No house assigned.</p>
        @endif
      </div>
    </div>

    <!-- Payments Tab -->
    <!-- Payments Tab -->
    <div class="tab-pane fade" id="payments" role="tabpanel">
      <div class="card p-3 bg-white shadow-sm">
        <h4 class="text-dark fw-bold mb-3">Payments</h4>

        <!-- Payment Summary -->
        <div class="row mb-4">
          <div class="col-md-4">
            <div class="card shadow-sm p-3 text-center bg-light">
              <h6 class="fw-bold text-dark">Total Due</h6>
              <p class="fs-5 text-danger">₱{{ number_format($tenant->unit->price ?? 0, 2) }}</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card shadow-sm p-3 text-center bg-light">
              <h6 class="fw-bold text-dark">Total Paid</h6>
              <p class="fs-5 text-success">₱{{ number_format($payments->sum('amount'), 2) }}</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card shadow-sm p-3 text-center bg-light">
              <h6 class="fw-bold text-dark">Outstanding Balance</h6>
              <p class="fs-5 text-warning">₱{{ number_format(($tenant->unit->price ?? 0) - $payments->sum('amount'), 2) }}</p>
            </div>
          </div>
        </div>

        <!-- Payment Table -->
        @if($payments->isEmpty())
        <p class="text-dark">No payments yet.</p>
        @else
        <table class="table table-striped">
          <thead class="table-dark">
            <tr>
              <th>Month</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Method</th>
              <th>Reference</th>
            </tr>
          </thead>
          <tbody>
            @foreach($payments as $payment)
            <tr>
              <td>{{ \Carbon\Carbon::parse($payment->date)->format('F Y') }}</td>
              <td>₱{{ number_format($payment->amount, 2) }}</td>
              <td>
                @if($payment->status == 'paid')
                <span class="badge bg-success">Paid</span>
                @elseif($payment->status == 'pending')
                <span class="badge bg-warning">Pending</span>
                @else
                <span class="badge bg-danger">{{ ucfirst($payment->status) }}</span>
                @endif
              </td>
              <td>{{ $payment->method ?? '-' }}</td>
              <td>{{ $payment->reference ?? '-' }}</td>
            </tr>
            @endforeach
          </tbody>

        </table>
        @endif
        <!-- Pay Now Button -->
        <div class="mt-3 text-end">
          <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#payNowModal">
            💳 Pay Now
          </button>
        </div>
      </div>
    </div>

    <!-- Pay Now Modal -->
    <div class="modal fade" id="payNowModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title">Make a Payment</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('payments.create', ['tenant' => $tenant->id]) }}" method="POST">
              @csrf
              <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" step="0.01" class="form-control" name="amount" required>
              </div>
              <div class="mb-3">
                <label for="method" class="form-label">Payment Method</label>
                <select class="form-select" name="method" required>
                  <option value="Cash">Cash</option>
                  <option value="GCash">GCash</option>
                  <option value="Bank Transfer">Bank Transfer</option>
                  <option value="PayPal">PayPal</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="reference" class="form-label">Reference No.</label>
                <input type="text" class="form-control" name="reference">
              </div>
              <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" name="notes" rows="2"></textarea>
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-success">Submit Payment</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>


    <!-- Lease Agreement Tab -->
    <div class="tab-pane fade" id="lease" role="tabpanel">
      <div class="card p-3 bg-white shadow-sm">
        <h4 class="text-dark fw-bold">Lease Agreement</h4>
        <p class="text-dark"><strong>Start Date:</strong> {{ $tenant->lease_start ?? 'N/A' }}</p>
        <p class="text-dark"><strong>End Date:</strong> {{ $tenant->lease_end ?? 'N/A' }}</p>
        <p class="text-dark"><strong>Monthly Rent:</strong> {{ $tenant->monthly_rent ?? 'N/A' }}</p>
      </div>
    </div>

    <!-- Maintenance Tab -->
    <div class="tab-pane fade" id="maintenance" role="tabpanel">
      <div class="card p-3 bg-white shadow-sm">
        <h4 class="text-dark fw-bold">Maintenance Requests</h4>
        <p class="text-dark">No maintenance records yet. (Placeholder)</p>
      </div>
    </div>
  </div>
</div>

<!-- Custom Styles -->
<style>
  .nav-tabs .nav-link.active {
    background-color: #0d3b2e !important;
    color: #fff !important;
    font-weight: bold;
  }

  .nav-tabs .nav-link {
    color: #000 !important;
  }

  .card {
    background-color: #fff !important;
    color: #000 !important;
  }

  .card p,
  .card h4,
  .card strong {
    color: #000 !important;
  }
</style>
@endsection