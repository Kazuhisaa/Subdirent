@extends('layout.applys')

@section('content')
<div class="container my-5 text-center">
  <h2 class="text-danger">❌ Payment Cancelled</h2>
  <p>Hi {{ $tenant->first_name }}, your payment was cancelled. Please try again.</p>
  <a href="{{ route('tenant.dashboard', $tenant->id) }}" class="btn btn-warning">Back to Dashboard</a>
</div>
@endsection