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
    <div class="tab-pane fade" id="payments" role="tabpanel">
      <div class="card p-3 bg-white shadow-sm">
        <h4 class="text-dark fw-bold">Payments</h4>
        @if($payments->isEmpty())
        <p class="text-dark">No payments yet.</p>
        @else
        <table class="table table-striped">
          <thead class="table-dark">
            <tr>
              <th>Date</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            @foreach($payments as $payment)
            <tr>
              <td>{{ $payment->date }}</td>
              <td>{{ $payment->amount }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
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