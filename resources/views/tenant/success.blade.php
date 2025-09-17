@extends('layout.applys')

@section('content')
<div class="container my-5 text-center">
  <h2 class="text-success">✅ Payment Successful!</h2>
  <p>Thank you, {{ $tenant->first_name }}. Your rent payment has been recorded.</p>
  <a href="{{ route('tenant.dashboard', $tenant->id) }}" class="btn btn-primary">Back to Dashboard</a>
</div>
@endsection