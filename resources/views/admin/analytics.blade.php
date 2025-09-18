@extends('admin.admin')

@section('content')
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
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chartInstance;

async function loadPrediction(type) {
  let predictionUrl = '';
  let historyUrl = 'http://127.0.0.1:8000/api/revenue/history';

  if (type === 'month') predictionUrl = "/api/revenue/predictionMonth";
  else if (type === 'quarter') predictionUrl = "/api/revenue/predictionQuarter";
  else if (type === 'annual') predictionUrl = "/api/revenue/predictionAnnual";

  try {
    const [predictionRes, historyRes] = await Promise.all([
      fetch(predictionUrl),
      fetch(historyUrl)
    ]);

    if (!predictionRes.ok || !historyRes.ok) throw new Error('Failed to fetch data');

    const predictionData = await predictionRes.json();
    const history = await historyRes.json();

    // Historical data
    const historyLabels = history.map(h => h.date);
    const historyValues = history.map(h => parseFloat(h.historical_revenue));

    // Predicted revenue
    const predictedRevenue = parseFloat(predictionData.prediction.replace(/,/g, ''));
    const predictedDate = predictionData.date;

    // Chart labels: historical + predicted
    const allLabels = [...historyLabels, predictedDate];

    // Historical data: only actual historical points
    const historicalData = [...historyValues, null];

    // Predicted data: null for historical points, then predicted revenue at last index
    const predictedData = Array(historyValues.length).fill(null);
    predictedData.push(predictedRevenue);

    if (chartInstance) chartInstance.destroy();

    const ctx = document.getElementById('revenueChart').getContext('2d');
    chartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels: allLabels,
        datasets: [
          {
            label: 'Historical Revenue',
            data: historicalData,
            borderColor: 'var(--pdogold)',
            backgroundColor: 'rgba(255,193,7,0.2)',
            tension: 0.3,
            pointRadius: 4
          },
          {
            label: 'Predicted Revenue',
            data: predictedData,
            borderColor: 'var(--pdogold)',
            backgroundColor: 'rgba(255,193,7,0.3)',
            pointRadius: 6,
            showLine: false // only marker
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'top' },
          tooltip: { mode: 'index', intersect: false }
        },
        scales: {
          y: { beginAtZero: true }
        }
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
@endsection
