<!-- resources/views/admin/admin.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SubdiRent Admin</title>
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

    .sidebar {
      width: 60px;
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

    /* Fix sidebar text visibility for inactive links */
    .sidebar .btn:not(.text-white) {
      color: #184d2b;
      background-color: #e9ecef;
    }

    .sidebar .btn.text-white {
      color: #fff !important;
      background-color: var(--pdogreen);
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
      <a href="{{ route('admin.dashboard') }}" class="btn fw-semibold">
        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
      </a>
      <a href="{{ route('admin.tenants') }}" class="btn fw-semibold">
        <i class="bi bi-people-fill"></i> <span>Tenants</span>
      </a>
      <a href="{{ route('admin.units') }}" class="btn fw-semibold">
        <i class="bi bi-building"></i> <span>Units</span>
      </a>
      <a href="{{ route('admin.bookings') }}" class="btn fw-semibold">
        <i class="bi bi-journal-check"></i> <span>Bookings</span>
      </a>
      <a href="{{ route('admin.payments') }}" class="btn fw-semibold">
        <i class="bi bi-cash-stack"></i> <span>Payments</span>
      </a>
      <a href="{{ route('admin.maintenance') }}" class="btn fw-semibold">
        <i class="bi bi-tools"></i> <span>Maintenance</span>
      </a>
      <a href="{{ route('admin.analytics') }}" class="btn fw-semibold">
        <i class="bi bi-graph-up"></i> <span>Analytics</span>
      </a>
    </div>

    <!-- Toggle button (absolute inside flex container) -->
    <button id="sidebarToggle" style="position:absolute; top:10px; left:10px; z-index:1000; background-color: var(--pdogreen); border:none; color:white;">
      <i class="bi bi-list"></i>
    </button>

    <!-- Main content placeholder -->
    <div class="content">
      @yield('content')
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    // Toggle sidebar expand/collapse
    toggleBtn.addEventListener('click', () => sidebar.classList.toggle('expanded'));

    // Sidebar link highlight on click (only <a> tags)
    const sidebarLinks = sidebar.querySelectorAll('a');
    sidebarLinks.forEach(link => {
      link.addEventListener('click', function() {
        // Remove highlight from all links
        sidebarLinks.forEach(l => {
          l.classList.remove('text-white');
          l.style.backgroundColor = '';
        });
        // Highlight the clicked link
        this.classList.add('text-white');
        this.style.backgroundColor = 'var(--pdogreen)';
      });
    });
  </script>

  @yield('scripts')
</body>

</html>