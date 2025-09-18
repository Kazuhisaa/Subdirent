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
      <div class="container my-2">

        <!-- Summary Cards -->
        <div class="row mb-4 g-3">
          <div class="col-md-4">
            <div class="card text-center p-4 shadow-sm h- 150">
              <h6 class="text-muted">Total Due</h6>
              <h4>₱{{ number_format($totalDue, 2) }}</h4>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card text-center p-4 shadow-sm h-150">
              <h6 class="text-muted">Total Paid</h6>
              <h4>₱{{ number_format($totalPaid, 2) }}</h4>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card text-center p-4 shadow-sm h-150">
              <h6 class="text-muted">Outstanding Balance</h6>
              <h4>₱{{ number_format($outstanding, 2) }}</h4>
            </div>
          </div>
        </div>

        <!-- Main Content Row -->
        <div class="row g-4">
          <!-- Left: Payment History -->
          <div class="col-md-8 h-150">
            <div class="card p-3 shadow-sm">
              <h5 class="mb-3">Payment History</h5>
              <table class="table table-striped mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Month</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Method</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($payments as $payment)
                  <tr>
                    <td>{{ \Carbon\Carbon::parse($payment->for_month)->format('F Y') ?? '-' }}</td>
                    <td>₱{{ number_format($payment->amount, 2) }}</td>
                    <td>{{ ucfirst($payment->status) }}</td>
                    <td>{{ $payment->method }}</td>
                    <td>{{ $payment->payment_date }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <!-- Right: Pay Now + AutoPay -->
          <div class="col-md-4 d-flex flex-column gap-3">

            <!-- Pay Now Card -->
            <div class="card p-4 shadow-sm text-center">
              <h5 class="mb-3">Next Payment</h5>
              <p class="mb-2">{{ $nextMonth['date'] ?? 'Next Payment Date' }}</p>
              <p class="h4 mb-3">₱{{ number_format($nextMonth['amount'] ?? $tenant->monthly_rent, 2) }}</p>

              @if(isset($nextMonth['for_month']))
              <form method="POST" action="{{ route('payments.create', $tenant->id) }}">
                @csrf
                <input type="hidden" name="for_month" value="{{ $nextMonth['for_month'] }}">

                <div class="mb-3">
                  <label for="payment_method" class="form-label">Select Payment Method</label>
                  <select name="payment_method" id="payment_method" class="form-select" required>
                    <option value="" disabled selected>-- Choose Payment Method --</option>
                    <option value="gcash">GCash</option>
                    <option value="card">Credit/Debit Card</option>
                  </select>
                </div>

                <button type="submit" class="btn btn-success w-100">
                  Pay Now
                </button>
              </form>
              @endif
            </div>

            <!-- AutoPay Card -->
            <div class="card p-4 shadow-sm text-center">
              <h5 class="mb-3">Auto Payment</h5>
              <p class="mb-2">Set up recurring payments</p>

              <form method="POST" action="{{ route('autopay.setup', $tenant->id) }}">
                @csrf
                <div class="mb-3">
                  <label for="autopay_method" class="form-label">Payment Method</label>
                  <select name="method" id="autopay_method" class="form-select" required>
                    <option value="" disabled selected>-- Choose --</option>
                    <option value="gcash">GCash</option>
                    <option value="card">Credit/Debit Card</option>
                    <option value="bank">Bank Account</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="autopay_day" class="form-label">Payment Day</label>
                  <select name="day_of_month" id="autopay_day" class="form-select" required>
                    @for($i = 1; $i <= 28; $i++)
                      <option value="{{ $i }}">{{ $i }}</option>
                      @endfor
                  </select>
                </div>

                <button type="submit" class="btn btn-warning w-100">
                  Enable AutoPay
                </button>
              </form>
            </div>

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

@section('scripts')
<script>
  const autopayCheckbox = document.getElementById('autopayCheckbox');
  const autopayOptions = document.getElementById('autopayOptions');
  const paymentMethodSelect = document.getElementById('paymentMethodSelect');
  const paymentDetails = document.getElementById('paymentDetails');
  const gcashFields = document.getElementById('gcashFields');
  const cardFields = document.getElementById('cardFields');

  function togglePaymentDetails() {
    const method = paymentMethodSelect.value;
    paymentDetails.style.display = method ? 'block' : 'none';
    gcashFields.style.display = method === 'gcash' ? 'block' : 'none';
    cardFields.style.display = method === 'card' ? 'block' : 'none';
  }

  // Checkbox toggle
  autopayCheckbox.addEventListener('change', function() {
    autopayOptions.style.display = this.checked ? 'block' : 'none';
    if (!this.checked) {
      paymentDetails.style.display = 'none';
      paymentMethodSelect.value = '';
    } else {
      togglePaymentDetails();
    }
  });

  // Payment method select
  paymentMethodSelect.addEventListener('change', togglePaymentDetails);

  // Initialize display on page load
  if (autopayCheckbox.checked) {
    togglePaymentDetails();
  }

  // Form submit
  document.getElementById('autopayForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    fetch(`/api/tenants/{{ $tenant->id }}/autopay`, {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        alert('✅ Autopay settings saved!');
      })
      .catch(err => alert('❌ Error saving autopay settings'));
  });
</script>
@endsection