<?php
namespace App\Services;

use Phpml\Metric\Regression as RegressionMetric;
use App\Models\RevenuePrediction;
use Phpml\Regression\LeastSquares;
use Carbon\Carbon;

class RevenuePredictionService
{
    public function predictMonthly()
    {
        $dataset = RevenuePrediction::all()->toArray();
        $features = [];
        $targets = [];
        $length = count($dataset);

        for ($i = 1; $i < $length; $i++) {
            $prev = $dataset[$i - 1];
            $curr = $dataset[$i];

            // Include year, month, and previous revenue
            $features[] = [$curr['year'], $curr['month'], $prev['historical_revenue']];
            $targets[] = $curr['historical_revenue'];
        }

        $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = end($dataset);
        $futureYear = $last['year']+1;
        $futureMonth = $last['month'] + 1;
        if ($futureMonth > 12) {
            $futureMonth = 1;
         
        }

        $predicted = $regression->predict([
            $futureYear,
            $futureMonth,
            $last['historical_revenue']
        ]);

        $lastDate = Carbon::parse($last['date']);
        $nextDate = $lastDate->copy()->addMonth()->format('Y-m-d');

        return [
            'prediction' => number_format($predicted, 2),
            'date' => $nextDate
        ];
    }

    public function predictQuarterly()
    {
        $dataset = RevenuePrediction::all()->toArray();
        $features = [];
        $targets = [];
        $length = count($dataset);

        for ($i = 3; $i < $length; $i++) {
            $curr = $dataset[$i];
            $features[] = [
                $curr['year'], $curr['month'],
                $dataset[$i-1]['historical_revenue'],
                $dataset[$i-2]['historical_revenue'],
                $dataset[$i-3]['historical_revenue']
            ];
            $targets[] = $curr['historical_revenue'];
        }

        $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = array_slice($dataset, -3);

        $futureYear = $last[2]['year']+3;
        $futureMonth = $last[2]['month'] + 3;
        if ($futureMonth > 12) {
            $futureMonth -= 12;

        }

        $predicted = $regression->predict([
            $futureYear,
            $futureMonth,
            $last[2]['historical_revenue'],
            $last[1]['historical_revenue'],
            $last[0]['historical_revenue']
        ]);

        $lastDate = Carbon::parse($last[2]['date']);
        $nextDate = $lastDate->copy()->addMonths(3)->format('Y-m-d');

        return [
            'prediction' => number_format($predicted, 2),
            'date' => $nextDate
        ];
    }

    public function predictAnnual()
    {
        $dataset = RevenuePrediction::all()->toArray();
        $features = [];
        $targets = [];
        $length = count($dataset);

        for ($i = 12; $i < $length; $i++) {
            $curr = $dataset[$i];
            $feature = [$curr['year'], $curr['month']];
            for ($j = 1; $j <= 12; $j++) {
                $feature[] = $dataset[$i - $j]['historical_revenue'];
            }
            $features[] = $feature;
            $targets[] = $curr['historical_revenue'];
        }

        $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = array_slice($dataset, -12);

        $futureYear = $last[11]['year'] + 12;
        $futureMonth = $last[11]['month']+12;
        if ($futureMonth > 12) {
            $futureMonth -= 12;
        }

        $futureFeature = [$futureYear, $futureMonth];
        for ($i = 11; $i >= 0; $i--) {
            $futureFeature[] = $last[$i]['historical_revenue'];
        }

        $predicted = $regression->predict($futureFeature);

        $lastDate = Carbon::parse($last[11]['date']);
        $nextDate = $lastDate->copy()->addMonths(12)->format('Y-m-d');

        return [
            'prediction' => number_format($predicted, 2),
            'date' => $nextDate
        ];
    }
}
