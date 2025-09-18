@extends('admin.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="container py-4">
        <h2 class="mb-2">
            <i class="bi bi-journal-bookmark me-2"></i> Bookings
        </h2>
        <!-- Optional: Month filter -->
        {{--
        <form method="GET" action="{{ route('admin.bookings') }}" class="d-flex mb-3">
        <input type="month" name="month" value="{{ $month ?? '' }}" class="form-control me-2" style="max-width:200px;">
        <button type="submit" class="btn btn-success">
            <i class="bi bi-funnel me-1"></i> Filter
        </button>
        </form>
        --}}
    </div>

    <!-- Bookings Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-success text-center">
                        <tr>
                            <th>ID</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Date</th>
                            <th>Time Slot</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                        <tr>
                            <td class="text-center">{{ $booking->id }}</td>
                            <td>{{ $booking->unit->title ?? '—' }}</td>
                            <td>₱{{ number_format($booking->price, 2) }}</td>
                            <td>{{ $booking->full_name }}</td>
                            <td>{{ $booking->email }}</td>
                            <td>{{ $booking->contact }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->date)->format('M d, Y') }}</td>
                            <td>{{ $booking->time_slot }}</td>
                            <td>{{ $booking->notes ?? '—' }}</td>
                            <td class="text-center">
                                @php $status = $booking->status ?? 'pending'; @endphp
                                <span class="badge rounded-pill 
                                    {{ $status === 'confirmed' ? 'bg-success' : '' }}
                                    {{ $status === 'pending' ? 'bg-warning text-dark' : '' }}
                                    {{ $status === 'denied' ? 'bg-danger' : '' }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-success btn-sm rounded-pill me-1" disabled>Confirm</button>
                                <button class="btn btn-danger btn-sm rounded-pill" disabled>Deny</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                No bookings found.
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