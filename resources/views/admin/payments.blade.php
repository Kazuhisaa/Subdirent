@extends('admin.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="container py-4">
        <h2 class="mb-4">
            <i class="bi bi-cash-stack me-2"></i> Tenant Payments
        </h2>
        <!-- Month filter -->
        <form method="GET" action="{{ route('admin.payments') }}" class="d-flex">
            <input type="month" name="month" value="{{ $month }}" class="form-control me-2" style="max-width:200px;">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>Tenant</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>House</th>
                            <th>Monthly Rent</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tenants as $tenant)
                        @php
                        $payment = $tenant->payments->first();
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $tenant->first_name }} {{ $tenant->last_name }}</td>
                            <td>{{ $tenant->email ?? '—' }}</td>
                            <td>{{ $tenant->contact ?? '—' }}</td>
                            <td>{{ $tenant->unit->title ?? '—' }}</td>
                            <td>₱{{ number_format($tenant->monthly_rent, 2) }}</td>
                            <td>
                                @if($payment)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i> Paid
                                </span>
                                @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle me-1"></i> Unpaid
                                </span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth()->format('F d, Y') }}</td>
                            <td class="text-center">
                                @if(!$payment)
                                <a href="#" class="btn btn-warning btn-sm">
                                    <i class="bi bi-envelope"></i> Send Reminder
                                </a>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                No tenants found for this month.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection