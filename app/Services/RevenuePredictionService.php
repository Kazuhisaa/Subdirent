<?php

namespace App\Services;

use App\Models\RevenuePrediction;
use Phpml\Regression\LeastSquares;
use Phpml\Metric\Regression as RegressionMetric;

class RevenuePredictionService
{
    public function predictMonthly()
    {
        $revenueDataset = RevenuePrediction::all()->toArray();
        $features = [];
        $targets = [];
        $length = count($revenueDataset);

        for ($i = 1; $i < $length; $i++) {
            $prev = $revenueDataset[$i - 1];
            $curr = $revenueDataset[$i];

            $features[] = [$curr['year'], $curr['month'], $prev['historical_revenue']];
            $targets[] = $curr['historical_revenue'];
        }

        $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = end($revenueDataset);
        $futureYear = $last['year']; // or adjust based on your dataset
        $futureMonth = $last['month'] + 1;

        $predicted = $regression->predict([
            $futureYear, $futureMonth, $last['historical_revenue']
        ]);

        return [
            'prediction (monthly)' => number_format($predicted,2)   
            'prediction for next month' => number_format($predicted, 2)
        ];
    }

    public function predictQuarterly()
    {
        $revenueDataset = RevenuePrediction::all()->toArray();
        $features = [];
        $targets = [];
        $length = count($revenueDataset);

        for ($i = 3; $i < $length; $i++) {
            $curr = $revenueDataset[$i];
            $features[] = [
                $curr['year'], $curr['month'],
                $revenueDataset[$i-1]['historical_revenue'],
                $revenueDataset[$i-2]['historical_revenue'],
                $revenueDataset[$i-3]['historical_revenue']
            ];
            $targets[] = $curr['historical_revenue'];
        }

        $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = array_slice($revenueDataset, -3);
        $futureYear = end($revenueDataset)['year'];
        $futureMonth = end($revenueDataset)['month'] + 3;

        $predicted = $regression->predict([
            $futureYear, $futureMonth,
            $last[2]['historical_revenue'],
            $last[1]['historical_revenue'],
            $last[0]['historical_revenue']
        ]);

        return [
            'prediction (quarterly)' => number_format($predicted,2)   
            'prediction for next 3 months' => number_format($predicted, 2)
        ];
    }

    public function predictAnnual()
    {
        $revenueDataset = RevenuePrediction::all()->toArray();
        $features = [];
        $targets = [];
        $length = count($revenueDataset);

        for ($i = 12; $i < $length; $i++) {
            $curr = $revenueDataset[$i];
            $features[] = [
                $curr['year'], $curr['month'],
                $revenueDataset[$i-1]['historical_revenue'],
                $revenueDataset[$i-2]['historical_revenue'],
                $revenueDataset[$i-3]['historical_revenue'],
                $revenueDataset[$i-4]['historical_revenue'],
                $revenueDataset[$i-5]['historical_revenue'],
                $revenueDataset[$i-6]['historical_revenue'],
                $revenueDataset[$i-7]['historical_revenue'],
                $revenueDataset[$i-8]['historical_revenue'],
                $revenueDataset[$i-9]['historical_revenue'],
                $revenueDataset[$i-10]['historical_revenue'],
                $revenueDataset[$i-11]['historical_revenue'],
                $revenueDataset[$i-12]['historical_revenue']
            ];
            $targets[] = $curr['historical_revenue'];
        }

        $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = array_slice($revenueDataset, -12);
        $futureYear = end($revenueDataset)['year'];
        $futureMonth = end($revenueDataset)['month'] + 12;

        $predicted = $regression->predict([
            $futureYear, $futureMonth,
            $last[11]['historical_revenue'],
            $last[10]['historical_revenue'],
            $last[9]['historical_revenue'],
            $last[8]['historical_revenue'],
            $last[7]['historical_revenue'],
            $last[6]['historical_revenue'],
            $last[5]['historical_revenue'],
            $last[4]['historical_revenue'],
            $last[3]['historical_revenue'],
            $last[2]['historical_revenue'],
            $last[1]['historical_revenue'],
            $last[0]['historical_revenue']
        ]);

        $predictedAll = array_map(fn($x) => $regression->predict($x), $features);
        $r2 = RegressionMetric::r2Score($targets, $predictedAll);
          return [
            'prediction (Annually)' => number_format($predicted,2)

        return [
            'prediction for next 12 months' => number_format($predicted, 2),
            'r2_score' => round($r2, 2)
        ];
    }
}
