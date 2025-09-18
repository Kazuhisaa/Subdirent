@extends('admin.admin')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Bookings</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($bookings->isEmpty())
        <div class="alert alert-info">No bookings found.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
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
                    @foreach ($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->unit->title ?? 'N/A' }}</td>
                            <td>₱{{ number_format($booking->price, 2) }}</td>
                            <td>{{ $booking->full_name }}</td>
                            <td>{{ $booking->email }}</td>
                            <td>{{ $booking->contact }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->date)->format('M d, Y') }}</td>
                            <td>{{ $booking->time_slot }}</td>
                            <td>{{ $booking->notes ?? '—' }}</td>
                            <td>
                                <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'denied' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td>
                                <!-- Placeholder buttons -->
                                <button class="btn btn-success btn-sm" disabled>Confirm</button>
                                <button class="btn btn-danger btn-sm" disabled>Deny</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
