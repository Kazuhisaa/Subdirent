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

            $features[] = [$curr['Year'], $curr['Month'], $prev['Historical Revenue']];
            $targets[] = $curr['Historical Revenue'];
        }

        $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = end($revenueDataset);
        $futureYear = $revenueDataset[$length-1]['Year'] + 1;
        $futureMonth = $revenueDataset[$length-1]['Month'] + 1;

        $predicted = $regression->predict([
            $futureYear, $futureMonth, $last['Historical Revenue']
        ]);

        // $predictedAll = array_map(fn($x) => $regression->predict($x), $features);
        // $r2 = RegressionMetric::r2Score($targets, $predictedAll);

        return [
            'prediction (monthly)' => number_format($predicted,2)   
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
                $curr['Year'], $curr['Month'],
                $revenueDataset[$i-1]['Historical Revenue'],
                $revenueDataset[$i-2]['Historical Revenue'],
                $revenueDataset[$i-3]['Historical Revenue']
            ];
            $targets[] = $curr['Historical Revenue'];
        }

        $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = array_slice($revenueDataset, -3);
        $futureYear = $revenueDataset[$length-1]['Year'] + 3;
        $futureMonth = $revenueDataset[$length-1]['Month'] + 3;

        $predicted = $regression->predict([
            $futureYear, $futureMonth,
            $last[2]['Historical Revenue'],
            $last[1]['Historical Revenue'],
            $last[0]['Historical Revenue']
        ]);

        // $predictedAll = array_map(fn($x) => $regression->predict($x), $features);
        // $r2 = RegressionMetric::r2Score($targets, $predictedAll);

        return [
            'prediction (quarterly)' => number_format($predicted,2)   
        ];
    }




    public function predictAnnual(){
         $revenueDataset = RevenuePrediction::all()->toArray();
        $features = [];
        $targets = [];
        $length = count($revenueDataset);


          for ($i = 12; $i < $length; $i++) {
            $curr = $revenueDataset[$i];
            $features[] = [
                $curr['Year'], $curr['Month'],
                $revenueDataset[$i-1]['Historical Revenue'],
                $revenueDataset[$i-2]['Historical Revenue'],
                $revenueDataset[$i-3]['Historical Revenue'],
                $revenueDataset[$i-4]['Historical Revenue'],
                $revenueDataset[$i-5]['Historical Revenue'],
                $revenueDataset[$i-6]['Historical Revenue'],
                $revenueDataset[$i-7]['Historical Revenue'],
                $revenueDataset[$i-8]['Historical Revenue'],
                $revenueDataset[$i-9]['Historical Revenue'],
                $revenueDataset[$i-10]['Historical Revenue'],
                $revenueDataset[$i-11]['Historical Revenue'],
                $revenueDataset[$i-12]['Historical Revenue']
            ];
            $targets[] = $curr['Historical Revenue'];
        }

          $regression = new LeastSquares();
        $regression->train($features, $targets);

        $last = array_slice($revenueDataset, -12);
        $futureYear = $revenueDataset[$length-1]['Year'] + 12;
        $futureMonth = $revenueDataset[$length-1]['Month'] + 12;
      

         $predicted = $regression->predict([
            $futureYear, $futureMonth,
            $last[11]['Historical Revenue'],
            $last[10]['Historical Revenue'],
            $last[9]['Historical Revenue'],
            $last[8]['Historical Revenue'],
            $last[7]['Historical Revenue'],
            $last[6]['Historical Revenue'],
            $last[5]['Historical Revenue'], 
            $last[4]['Historical Revenue'],
            $last[3]['Historical Revenue'],
            $last[2]['Historical Revenue'],
            $last[1]['Historical Revenue'],
            $last[0]['Historical Revenue']
        ]);

       $predictedAll = array_map(fn($x) => $regression->predict($x), $features);
        $r2 = RegressionMetric::r2Score($targets, $predictedAll);
          return [
            'prediction (Annually)' => number_format($predicted,2)
        ];
    }
}
