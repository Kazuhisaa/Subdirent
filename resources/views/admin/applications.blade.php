@extends('admin.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="container py-4">
        <h2 class="mb-4">
            <i class="bi bi-file-earmark-text me-2"></i> Applications
        </h2>
    </div>

    <!-- Applications Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-success text-center">
                        <tr>
                            <th>Title</th>
                            <th>ID</th>
                            <th>Applicant</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Unit</th>
                            <th>Lease Start</th>
                            <th>Lease Duration</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                        <tr>
                            <td>{{ $app->title }}</td>
                            <td class="text-center">{{ $app->id }}</td>
                            <td>{{ $app->surname }}, {{ $app->first_name }} {{ $app->middle_name }}</td>
                            <td>{{ $app->email }}</td>
                            <td>{{ $app->contact_number }}</td>
                            <td>{{ $app->unit->title ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($app->lease_start)->format('M d, Y') }}</td>
                            <td>{{ $app->lease_duration }} months</td>
                            <td class="text-center">
                                @php $status = $app->status ?? 'pending'; @endphp
                                <span class="badge rounded-pill
                                    {{ $status === 'accepted' ? 'bg-success' : '' }}
                                    {{ $status === 'pending' ? 'bg-warning text-dark' : '' }}
                                    {{ $status === 'declined' ? 'bg-danger' : '' }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($status === 'pending')
                                <form action="{{ route('applications.accept', $app->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-success btn-sm rounded-pill me-1">Accept</button>
                                </form>
                                <form action="{{ route('applications.decline', $app->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-danger btn-sm rounded-pill">Decline</button>
                                </form>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                No applications yet.
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