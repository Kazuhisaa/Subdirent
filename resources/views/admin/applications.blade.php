@extends('admin.admin')

@section('content')
<div class="container mt-4">
    <h2>Applications</h2>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif


    <table class="table table-bordered mt-3">
        <thead>
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
                <td>{{ $app->id }}</td>
                <td>{{ $app->surname }}, {{ $app->first_name }} {{ $app->middle_name }}</td>
                <td>{{ $app->email }}</td>
                <td>{{ $app->contact_number }}</td>
                <td>{{ $app->unit->title ?? 'N/A' }}</td>
                <td>{{ $app->lease_start }}</td>
                <td>{{ $app->lease_duration }} months</td>
                <td>{{ ucfirst($app->status ?? 'pending') }}</td>
                <td>
                    @if(($app->status ?? 'pending') === 'pending')
                        <form action="{{ route('applications.accept', $app->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-success btn-sm">Accept</button>
                        </form>
                        <form action="{{ route('applications.decline', $app->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-danger btn-sm">Decline</button>
                        </form>
                    @else
                        {{ ucfirst($app->status) }}
                    @endif
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No applications yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
