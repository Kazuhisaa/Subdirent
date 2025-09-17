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
    let url = '',
      label = '';
    if (type === 'month') {
      url = "/api/revenue/predictionMonth";
      label = "Next Month";
    } else if (type === 'quarter') {
      url = "/api/revenue/predictionQuarter";
      label = "Next Quarter";
    } else if (type === 'annual') {
      url = "/api/revenue/predictionAnnual";
      label = "Next Year";
    }

    try {
      const [predictionRes, historyRes] = await Promise.all([
        fetch(url),
        fetch("/api/revenue/history")
      ]);

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
          datasets: [{
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
              borderDash: [5, 5],
              tension: 0.3,
              pointRadius: 6
            }
          ]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top'
            },
            tooltip: {
              mode: 'index',
              intersect: false
            }
          },
          scales: {
            y: {
              beginAtZero: true
            }
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