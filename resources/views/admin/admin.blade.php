<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SubdiRent Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  @vite(['resources/css/admin.css'])
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    :root {
      --pdogreen: #184d2b;
      --pdogold: #ffc107;
    }

    body {
      overflow-x: hidden;
    }

    .d-flex-full {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      width: 60px; /* collapsed */
      transition: width 0.3s;
      overflow: hidden;
      background-color: #f8f9fa;
      border-right: 1px solid #dee2e6;
      padding-top: 10px;
    }

    .sidebar.expanded {
      width: 220px;
    }

    .sidebar .btn {
      display: flex;
      align-items: center;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 10px;
      transition: all 0.3s;
    }

    .sidebar .btn i {
      margin-right: 0;
      transition: margin 0.3s;
    }

    .sidebar.expanded .btn i {
      margin-right: 10px;
    }

    .sidebar.expanded .btn span {
      display: inline;
    }

    .sidebar .btn span {
      display: none;
    }

    .content {
      flex-grow: 1;
      padding: 20px;
      transition: margin-left 0.3s;
    }

    /* Toggle button */
    #sidebarToggle {
      position: absolute;
      top: 10px;
      left: 10px;
      z-index: 1000;
      background-color: var(--pdogreen);
      border: none;
      color: white;
    }
  </style>
</head>
<body class="bg-light">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: var(--pdogreen);">
    <div class="container-fluid">
      <span class="navbar-brand fw-bold">SubdiRent Admin</span>
      <div class="d-flex align-items-center">
        <button class="btn btn-outline-light">Logout</button>
      </div>
    </div>
  </nav>

  <div class="d-flex-full position-relative">
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar d-flex flex-column p-2">
      <a href="#" class="btn fw-semibold text-white" style="background-color: var(--pdogreen);">
        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
      </a>
      <a href="{{ route('admin.tenants') }}" class="btn btn-outline-success fw-semibold">
        <i class="bi bi-people-fill"></i> <span>Tenants</span>
      </a>
      <a href="{{ route('admin.units') }}" class="btn btn-outline-success fw-semibold">
        <i class="bi bi-building"></i> <span>Units</span>
      </a>
      <a href="#" class="btn btn-outline-success fw-semibold">
        <i class="bi bi-journal-check"></i> <span>Bookings</span>
      </a>
      <a href="#" class="btn btn-outline-success fw-semibold">
        <i class="bi bi-cash-stack"></i> <span>Payments</span>
      </a>
      <a href="#" class="btn btn-outline-success fw-semibold">
        <i class="bi bi-tools"></i> <span>Maintenance</span>
      </a>
      <a href="{{ route('admin.analytics') }}" class="btn btn-outline-success fw-semibold mt-auto">
        <i class="bi bi-graph-up"></i> <span>Analytics</span>
      </a>
    </div>

    <!-- Toggle button -->
    <button id="sidebarToggle"><i class="bi bi-list"></i></button>

    <!-- Main Content -->
    <div class="content">
      <h3 class="mb-4 fw-bold" style="color: var(--pdogreen);">Admin Dashboard</h3>

      <div class="row g-4">
        <div class="col-md-4">
          <div class="card shadow border-0">
            <div class="card-body text-center">
              <h5 class="card-title fw-bold" style="color: var(--pdogreen);">Total Tenants</h5>
              <p class="display-6 fw-bold text-dark">120</p>
              <button class="btn btn-sm text-white" style="background-color: var(--pdogreen);">View</button>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card shadow border-0">
            <div class="card-body text-center">
              <h5 class="card-title fw-bold" style="color: var(--pdogreen);">Available Units</h5>
              <p class="display-6 fw-bold text-dark">45</p>
              <button class="btn btn-sm btn-success">Manage</button>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card shadow border-0">
            <div class="card-body text-center">
              <h5 class="card-title fw-bold" style="color: var(--pdogreen);">Revenue</h5>
              <p class="display-6 fw-bold text-dark">₱500k</p>
              <button class="btn btn-sm btn-warning text-dark">Details</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('expanded');
    });
  </script>
</body>
</html>
