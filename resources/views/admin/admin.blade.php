<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SubdiRent Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <span class="navbar-brand fw-bold">SubdiRent Admin</span>
      <div class="d-flex align-items-center">
        <button class="btn btn-outline-light">Logout</button>
      </div>
    </div>
  </nav>

  <div class="d-flex vh-100">

    <!-- Sidebar -->
    <div class="bg-light border-end p-3" style="width: 220px;">
      <div class="d-grid gap-2">
        <a href="#" class="btn btn-outline-primary btn-block fw-semibold">Dashboard</a>
        <a href="{{ route('admin.tenants') }}" class="btn btn-outline-primary btn-block fw-semibold">Tenants</a>
        <a href="{{ route('admin.units') }}" class="btn btn-outline-primary btn-block fw-semibold">Units</a>
        <a href="#" class="btn btn-outline-primary btn-block fw-semibold">Bookings</a>
        <a href="#" class="btn btn-outline-primary btn-block fw-semibold">Payments</a>
        <a href="#" class="btn btn-outline-primary btn-block fw-semibold">Maintenance</a>
        <a href="#" class="btn btn-outline-primary btn-block fw-semibold">Analytics</a>
      </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>