<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tenant Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

  <div class="container py-5">
    <div class="row">

      <!-- Profile Info -->
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <img src="{{ $tenant->image ? asset('uploads/tenants/'.$tenant->image) : 'https://via.placeholder.com/150' }}"
              class="rounded-circle mb-3" width="120" height="120" alt="Profile Image">
            <h4>{{ $tenant->first_name }} {{ $tenant->middle_name }} {{ $tenant->last_name }}</h4>
            <p class="text-muted mb-1">{{ $tenant->email }}</p>
            <p class="text-muted">{{ $tenant->contact }}</p>
          </div>
        </div>
      </div>

      <!-- House + Lease + Payments -->
      <div class="col-md-8">
        <div class="card shadow-sm mb-3">
          <div class="card-header bg-primary text-white">🏠 House Information</div>
          <div class="card-body">
            @if($tenant->unit)
            <p><strong>Unit:</strong> {{ $tenant->unit->title }}</p>
            <p><strong>Description:</strong> {{ $tenant->unit->description }}</p>
            <p><strong>Monthly Rent:</strong> ₱{{ number_format($tenant->monthly_rent, 2) }}</p>
            @else
            <p class="text-danger">No unit assigned.</p>
            @endif
          </div>
        </div>

        <div class="card shadow-sm mb-3">
          <div class="card-header bg-warning">📄 Lease Agreement</div>
          <div class="card-body">
            <p><strong>Lease Start:</strong> {{ $tenant->lease_start }}</p>
            <p><strong>Lease End:</strong> {{ $tenant->lease_end }}</p>
            <p><strong>Notes:</strong> {{ $tenant->notes ?? '—' }}</p>
          </div>
        </div>

        <div class="card shadow-sm">
          <div class="card-header bg-success text-white">💰 Payment History</div>
          <div class="card-body">
            @if(isset($payments) && count($payments) > 0)
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead class="table-light">
                  <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($payments as $p)
                  <tr>
                    <td>{{ $p->payment_date }}</td>
                    <td>₱{{ number_format($p->amount, 2) }}</td>
                    <td>
                      <span class="badge {{ $p->status == 'paid' ? 'bg-success' : 'bg-danger' }}">
                        {{ ucfirst($p->status) }}
                      </span>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @else
            <p class="text-muted">No payments found.</p>
            @endif
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>