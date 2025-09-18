<?php
namespace App\Services;

use App\Models\RevenuePrediction;
use Phpml\Regression\LeastSquares;
use Carbon\Carbon;

class RevenuePredictionService
{
    public function predictmonthly()
    {
        $dataset = RevenuePrediction::all()->toArray();
        $features = [];
        $targets = [];
        $length = count($dataset);

        for ($i = 1; $i < $length; $i++) {
            $prev = $dataset[$i - 1];
            $curr = $dataset[$i];

            $features[] = [$prev['historical_revenue']];
            $targets[] = $curr['historical_revenue'];
        }

        $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = end($dataset);

        $predicted = $regression->predict([
            $last['historical_revenue']
        ]);

        $lastdate = Carbon::parse($last['date']);
        $nextdate = $lastdate->copy()->addmonth()->format('Y-m-d');

        return [
            'prediction' => number_format($predicted, 2),
            'date' => $nextdate
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
    
                $dataset[$i-1]['historical_revenue'],
                $dataset[$i-2]['historical_revenue'],
                $dataset[$i-3]['historical_revenue']
            ];
            $targets[] = $curr['historical_revenue'];
        }

        $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = array_slice($dataset, -3);
    
        $predicted = $regression->predict([
            $last[2]['historical_revenue'],
            $last[1]['historical_revenue'],
            $last[0]['historical_revenue']
        ]);

        $lastdate = Carbon::parse($last[2]['date']);
        $nextdate = $lastdate->copy()->addmonths(3)->format('Y-m-d');

        return [
            'prediction' => number_format($predicted, 2),
            'date' => $nextdate
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
            $feature = [];
            for ($j = 1; $j <= 12; $j++) {
                $feature[] = $dataset[$i - $j]['historical_revenue'];
            }
            $features[] = $feature;
            $targets[] = $curr['historical_revenue'];
        }

        $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = array_slice($dataset, -12);
 

        $futureFeature = [];
        for ($i = 11; $i >= 0; $i--) {
            $futureFeature[] = $last[$i]['historical_revenue'];
        }

        $predicted = $regression->predict($futureFeature);

        $lastdate = Carbon::parse($last[11]['date']);
        $nextdate = $lastdate->copy()->addmonths(12)->format('Y-m-d');

        return [
            'prediction' => number_format($predicted, 2),
            'date' => $nextdate
        ];
    }
}
