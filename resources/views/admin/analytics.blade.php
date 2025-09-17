<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analytics - SubdiRent Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  @vite(['resources/css/admin.css'])

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
<body>

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
      <a href="{{ route('admin.analytics') }}" class="btn fw-semibold text-white" style="background-color: var(--pdogreen);">
        <i class="bi bi-graph-up"></i> <span>Analytics</span>
      </a>
    </div>

    <!-- Toggle button -->
    <button id="sidebarToggle"><i class="bi bi-list"></i></button>

    <!-- Content -->
    <div class="content">
      <h3 class="mb-4 fw-bold" style="color: var(--pdogreen);">Revenue Analytics</h3>

      <!-- Buttons -->
      <div class="mb-3">
        <button class="btn text-white me-2" style="background-color: var(--pdogreen);" onclick="loadPrediction('month')">Monthly</button>
        <button class="btn btn-success me-2" onclick="loadPrediction('quarter')">Quarterly</button>
        <button class="btn btn-warning text-dark" onclick="loadPrediction('annual')">Annual</button>
      </div>

      <!-- Chart Card -->
      <div class="card shadow rounded-3 p-4">
        <canvas id="revenueChart" height="120"></canvas>
      </div>
    </div>
  </div>

<script>
const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('sidebarToggle');

toggleBtn.addEventListener('click', () => {
  sidebar.classList.toggle('expanded');
});

let chartInstance;

async function loadPrediction(type) {
  let url = '', label = '';
  if (type === 'month') { url = "/api/revenue/predictionMonth"; label = "Next Month"; }
  else if (type === 'quarter') { url = "/api/revenue/predictionQuarter"; label = "Next Quarter"; }
  else if (type === 'annual') { url = "/api/revenue/predictionAnnual"; label = "Next Year"; }

  try {
    const [predictionRes, historyRes] = await Promise.all([fetch(url), fetch("/api/revenue/history")]);
    if (!predictionRes.ok || !historyRes.ok) throw new Error('Failed to fetch data');

    const prediction = await predictionRes.json();
    const history = await historyRes.json();

    const historyLabels = history.map(h => `${h.year}-${String(h.month).padStart(2,'0')}`);
    const historyValues = history.map(h => h.historical_revenue);
    const predictionValue = Object.values(prediction)[0];

    const predictedData = historyValues.map((val, idx) => idx === historyValues.length - 1 ? val : null);
    predictedData.push(predictionValue);

    if (chartInstance) chartInstance.destroy();

    const ctx = document.getElementById('revenueChart').getContext('2d');
    chartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [...historyLabels, label],
        datasets: [
          {
            label: 'Historical Revenue',
            data: [...historyValues, null],
            borderColor: 'var(--pdogreen)',
            backgroundColor: 'rgba(24,77,43,0.2)',
            tension: 0.3,
            pointRadius: 4
          },
          {
            label: 'Predicted Revenue',
            data: predictedData,
            borderColor: 'var(--pdogold)',
            backgroundColor: 'rgba(255,193,7,0.3)',
            borderDash: [5,5],
            tension: 0.3,
            pointRadius: 6
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'top' },
          tooltip: { mode: 'index', intersect: false }
        },
        scales: { y: { beginAtZero: true } }
      }
    });

  } catch (err) {
    alert('Error loading chart: ' + err.message);
    console.error(err);
  }
}

// Load monthly data by default
loadPrediction('month');
</script>

</body>
</html>
